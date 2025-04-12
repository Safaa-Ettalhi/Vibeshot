<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
 
    public function index(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return view('search', ['users' => [], 'posts' => []]);
        }
        
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('username', 'like', "%{$query}%")
            ->take(10)
            ->get();
            
        $posts = Post::whereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('username', 'like', "%{$query}%");
            })
            ->orWhere('caption', 'like', "%{$query}%")
            ->with('user')
            ->take(10)
            ->get();
            
        return view('search', compact('users', 'posts', 'query'));
    }
}