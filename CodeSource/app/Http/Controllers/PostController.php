<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'likes', 'comments', 'images'])
            ->latest()
            ->get();

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'caption' => 'nullable|string|max:1000',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if (!$request->hasFile('images')) {
            return redirect()->back()->with('error', 'Please upload at least one image.');
        }

        $firstImage = $request->file('images')[0];
        $firstImagePath = $firstImage->store('posts', 'public');

        $post = new Post([
            'user_id' => auth()->id(),
            'caption' => $request->caption,
            'image_path' => $firstImagePath, 
        ]);

        $post->save();

        $order = 0;
        foreach ($request->file('images') as $image) {
            if ($order === 0) {
                $path = $firstImagePath;
            } else {
                $path = $image->store('posts', 'public');
            }
            
            $postImage = new PostImage([
                'post_id' => $post->id,
                'image_path' => $path,
                'order' => $order++
            ]);
            
            $postImage->save();
        }

        return redirect()->route('home')->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        $post->load(['user', 'likes', 'comments.user', 'images', 'originalPost.user', 'shares']);
        
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
      
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }
        if ($post->original_post_id) {
            return redirect()->back()->with('error', 'Shared posts cannot be edited.');
        }
        
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
{
    if ($post->user_id !== auth()->id()) {
        abort(403);
    }
    if ($post->original_post_id) {
        return redirect()->back()->with('error', 'Shared posts cannot be updated.');
    }

    Log::info('Update Post Request', [
        'post_id' => $post->id,
        'caption' => $request->caption,
        'has_images' => $request->hasFile('images')
    ]);
    
    $request->validate([
        'caption' => 'nullable|string|max:1000',
    ]);
    $post->caption = $request->caption;
    
    $imagesModified = $request->hasFile('images');

    if ($imagesModified) {
        
        $firstNewImage = $request->file('images')[0];
        $firstNewImagePath = $firstNewImage->store('posts', 'public');

        $post->image_path = $firstNewImagePath;
        
        $order = $post->images->count(); 
        
        foreach ($request->file('images') as $index => $image) {
            
            if ($index === 0) {
                $path = $firstNewImagePath;
            } else {
                $path = $image->store('posts', 'public');
            }
            
            $postImage = new PostImage([
                'post_id' => $post->id,
                'image_path' => $path,
                'order' => $order++
            ]);
            
            $postImage->save();
        }
    }
    
    $post->save();
    
    if ($post->shares->count() > 0) {
        foreach ($post->shares as $sharedPost) {
            
            $sharedPost->caption = $post->caption;
            $sharedPost->image_path = $post->image_path;
            $sharedPost->save();

            if ($imagesModified) {
                
                PostImage::where('post_id', $sharedPost->id)->delete();

                $originalImages = $post->fresh()->images; 
                $orderShared = 0;
                
                foreach ($originalImages as $originalImage) {
                    $postImage = new PostImage([
                        'post_id' => $sharedPost->id,
                        'image_path' => $originalImage->image_path,
                        'order' => $orderShared++
                    ]);
                    
                    $postImage->save();
                }
            }
        }
    }
    
    return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
}

public function destroyImage(PostImage $image)
{
    if ($image->post->user_id !== auth()->id()) {
        abort(403);
    }
    if ($image->post->original_post_id) {
        return redirect()->back()->with('error', 'Images from shared posts cannot be removed.');
    }
    
    $post = $image->post;
    $imagePath = $image->image_path;
    
    
    $isMainImage = ($post->image_path === $imagePath);
    
    
    $isUsedElsewhere = PostImage::where('image_path', $imagePath)
        ->where('id', '!=', $image->id)
        ->exists();
    
    if (!$isUsedElsewhere) {
        Storage::disk('public')->delete($imagePath);
    }
    
    $image->delete();
    
    
    if ($isMainImage) {
       
        $remainingImage = $post->fresh()->images->first();
        
        if ($remainingImage) {
            
            $post->image_path = $remainingImage->image_path;
        } else {
            
            $post->image_path = null;
        }
        
        $post->save();
    }
    
  
    if ($post->shares->count() > 0) {
        foreach ($post->shares as $sharedPost) {
            
            $sharedPost->image_path = $post->image_path;
            $sharedPost->save();

            PostImage::where('post_id', $sharedPost->id)->delete();

            $originalImages = $post->fresh()->images;
            $orderShared = 0;
            
            foreach ($originalImages as $originalImage) {
                $postImage = new PostImage([
                    'post_id' => $sharedPost->id,
                    'image_path' => $originalImage->image_path,
                    'order' => $orderShared++
                ]);
                
                $postImage->save();
            }
        }
    }
    
    return redirect()->back()->with('success', 'Image removed successfully!');
}

    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }
        DB::beginTransaction();
        
        try {
            if ($post->shares->count() > 0) {
                foreach ($post->shares as $sharedPost) {
                    
                    foreach ($sharedPost->images as $image) {
                        $image->delete();
                    }
                    
                    $sharedPost->delete();
                }
            }
            if ($post->image_path) {
               
                $isMainImageUsed = Post::where('image_path', $post->image_path)
                    ->where('id', '!=', $post->id)
                    ->exists();
                
                if (!$isMainImageUsed) {
                    Storage::disk('public')->delete($post->image_path);
                }
            }
            
           
            foreach ($post->images as $image) {
                
                $isImageUsed = PostImage::where('image_path', $image->image_path)
                    ->where('id', '!=', $image->id)
                    ->exists();
                
                if (!$isImageUsed) {
                    Storage::disk('public')->delete($image->image_path);
                }
                
                $image->delete();
            }
            
            $post->delete();
            
            DB::commit();
            
            return redirect()->route('home')->with('success', 'Post and all shared posts deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting post: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'An error occurred while deleting the post.');
        }
    }
    
    public function share(Post $post)
    {
        $sharedPost = new Post([
            'user_id' => auth()->id(),
            'caption' =>  ($post->caption ?? ''),
            'image_path' => $post->image_path,
            'original_post_id' => $post->id 
        ]);
        
        $sharedPost->save();
        
        if ($post->images->count() > 0) {
            $order = 0;
            foreach ($post->images as $image) {
                $postImage = new PostImage([
                    'post_id' => $sharedPost->id,
                    'image_path' => $image->image_path, 
                    'order' => $order++
                ]);
                
                $postImage->save();
            }
        }
        
        return redirect()->route('home')->with('success', 'Post shared successfully!');
    }
}