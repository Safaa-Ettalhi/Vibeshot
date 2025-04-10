<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{

    
    public function index()
    {
        $bookmarkedPosts = auth()->user()->bookmarks()
            ->with(['post.user', 'post.comments', 'post.likes'])
            ->latest()
            ->paginate(12);
            
        return view('bookmarks', compact('bookmarkedPosts'));
    }
    
    public function store(Post $post)
    {
       
        if ($post->bookmarks()->where('user_id', auth()->id())->exists()) {
            return redirect()->back();
        }
        
        $post->bookmarks()->create([
            'user_id' => auth()->id(),
        ]);
        
        return redirect()->back();
    }
    
    public function destroy(Post $post)
    {
        $post->bookmarks()->where('user_id', auth()->id())->delete();
        
        return redirect()->back();
    }
}