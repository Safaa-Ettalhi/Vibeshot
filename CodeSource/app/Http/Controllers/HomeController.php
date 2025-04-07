<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{


    public function index()
    {
        // Get posts from users that the authenticated user follows
        $followingIds = auth()->user()->following()->pluck('users.id');
        
        // Include the user's own posts
        $followingIds[] = auth()->id();
        
        $posts = Post::whereIn('user_id', $followingIds)
            ->with(['user', 'comments.user', 'likes'])
            ->latest()
            ->paginate(10);
            
        // Get trending posts (most liked in the last week)
        $trendingPosts = Post::withCount('likes')
            ->where('created_at', '>=', now()->subWeek())
            ->orderBy('likes_count', 'desc')
            ->take(3)
            ->get();
            
        // Get users that the authenticated user might know (not following yet)
        $suggestedUsers = User::whereNotIn('id', $followingIds)
            ->where('id', '!=', auth()->id())
            ->inRandomOrder()
            ->take(5)
            ->get();
            
        return view('home', compact('posts', 'trendingPosts', 'suggestedUsers'));
    }
}