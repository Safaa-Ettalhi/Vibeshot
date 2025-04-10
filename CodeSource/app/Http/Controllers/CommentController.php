<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

       
        if ($post->user_id !== auth()->id()) {
            $post->user->notify(new NewCommentNotification($post, $comment));
        }

        return redirect()->back();
    }

    public function destroy(Comment $comment)
    {
       
        
        $comment->delete();
        
        return redirect()->back();
    }
}