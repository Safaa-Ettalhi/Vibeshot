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
            ->get();
            
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
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'bookmarked' => true
            ]);
        }
        
        return redirect()->back();
    }
    
    public function destroy(Post $post)
    {
        $post->bookmarks()->where('user_id', auth()->id())->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'bookmarked' => false
            ]);
        }
        
        return redirect()->back();
    }
}