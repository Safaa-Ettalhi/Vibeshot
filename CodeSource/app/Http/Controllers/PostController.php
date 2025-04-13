<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostImage; // Importation correcte de la classe PostImage
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'likes', 'comments', 'images'])
            ->latest()
            ->paginate(10);

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
        
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
       
        if ($post->user_id !== auth()->id()) {
            abort(403);
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
        $post->save();
        

        if ($request->hasFile('images')) {
            $order = $post->images->count(); 
            
            foreach ($request->file('images') as $image) {
                $path = $image->store('posts', 'public');
                
                $postImage = new PostImage([
                    'post_id' => $post->id,
                    'image_path' => $path,
                    'order' => $order++
                ]);
                
                $postImage->save();
            }
        }
        
        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
    }

    public function destroyImage(PostImage $image)
    {
        if ($image->post->user_id !== auth()->id()) {
            abort(403);
        }
        
        
        Storage::disk('public')->delete($image->image_path);
        
       
        $image->delete();
        
        return redirect()->back()->with('success', 'Image removed successfully!');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }
        
        foreach ($post->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $post->delete();

        return redirect()->route('home')->with('success', 'Post deleted successfully!');
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
