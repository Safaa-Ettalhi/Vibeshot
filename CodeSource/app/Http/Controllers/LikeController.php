<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Notifications\NewLikeNotification;
use Illuminate\Http\Request;

class LikeController extends Controller
{

    
    public function store(Post $post)
    {
       
        if ($post->likes()->where('user_id', auth()->id())->exists()) {
            return redirect()->back();
        }
        
        $like = $post->likes()->create([
            'user_id' => auth()->id(),
        ]);
        
        
        if ($post->user_id !== auth()->id()) {
            $post->user->notify(new NewLikeNotification($post));
        }
        
        return redirect()->back();
    }
    
    public function destroy(Post $post)
    {
        $post->likes()->where('user_id', auth()->id())->delete();
        
        return redirect()->back();
    }
}