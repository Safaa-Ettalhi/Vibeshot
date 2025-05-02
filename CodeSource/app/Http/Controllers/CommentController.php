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

        if ($request->ajax()) {
            $html = view('partials.comment', compact('comment'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $post->comments()->count(),
                'post_id' => $post->id
            ]);
        }

        return redirect()->back();
    }
    
    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }
        
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        
        $comment->content = $request->content;
        $comment->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'content' => $comment->content
            ]);
        }
        
        return redirect()->back()->with('success', 'Commentaire mis à jour avec succès!');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }
        
        $post = $comment->post;
        $comment->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'count' => $post->comments()->count(),
                'post_id' => $post->id
            ]);
        }
        
        return redirect()->back();
    }
}
