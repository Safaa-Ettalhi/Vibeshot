<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostImage; // Importation correcte de la classe PostImage
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $post->load(['user', 'likes', 'comments.user', 'images']);
        
        return view('posts.show', compact('post'));
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
}