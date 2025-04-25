<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $followingIds = auth()->user()->following()->pluck('users.id');
        $followingIds[] = auth()->id();
        
        $postsQuery = Post::whereIn('user_id', $followingIds)
            ->where('is_hidden', false)
            ->with(['user', 'comments.user', 'likes', 'images']);
        $searchPerformed = false;

        if ($query) {
            $searchPerformed = true;
            $postsQuery = $postsQuery->where(function($q) use ($query) {
                $q->where('caption', 'LIKE', "%{$query}%")
                  ->orWhereHas('user', function($userQuery) use ($query) {
                      $userQuery->where('name', 'LIKE', "%{$query}%")
                                ->orWhere('username', 'LIKE', "%{$query}%");
                  });
            });
        }
        
        $posts = $postsQuery->latest()->get();
        $resultsCount = $posts->count();
        
        $trendingPosts = Post::withCount('likes')
            ->where('created_at', '>=', now()->subWeek())
            ->where('is_hidden', false)
            ->orderBy('likes_count', 'desc')
            ->take(3)
            ->get();
        
        $suggestedUsers = User::whereNotIn('id', $followingIds)
            ->where('id', '!=', auth()->id())
            ->where('is_blocked', false)
            ->inRandomOrder()
            ->take(5)
            ->get();
        
        return view('home', compact('posts', 'trendingPosts', 'suggestedUsers', 'query', 'resultsCount', 'searchPerformed'));
    }
}
