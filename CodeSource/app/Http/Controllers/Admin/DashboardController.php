<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $oneWeekAgo = Carbon::now()->subWeek();
        $usersCount = User::count();
        $newUsersCount = User::where('created_at', '>=', $oneWeekAgo)->count();
        $recentUsers = User::latest()->take(5)->get();
        
        // Statis des publications
        $postsCount = Post::count();
        $newPostsCount = Post::where('created_at', '>=', $oneWeekAgo)->count();
        $recentPosts = Post::with(['user', 'images'])->latest()->take(5)->get();
        
        // Statis des commentaires
        $commentsCount = Comment::count();
        $newCommentsCount = Comment::where('created_at', '>=', $oneWeekAgo)->count();
        $recentComments = Comment::with(['user', 'post', 'post.images'])->latest()->take(5)->get();
        
        // Statis des likes
        $likesCount = Like::count();
        $newLikesCount = Like::where('created_at', '>=', $oneWeekAgo)->count();

        $stats = [
            'users_count' => $usersCount,
            'new_users_count' => $newUsersCount,
            'recent_users' => $recentUsers,
            
            'posts_count' => $postsCount,
            'new_posts_count' => $newPostsCount,
            'recent_posts' => $recentPosts,
            
            'comments_count' => $commentsCount,
            'new_comments_count' => $newCommentsCount,
            'recent_comments' => $recentComments,
            
            'likes_count' => $likesCount,
            'new_likes_count' => $newLikesCount
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
}