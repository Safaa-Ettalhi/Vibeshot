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

        $comment = new Comment([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'content' => $request->content,
        ]);

        $comment->save();
        $comment->load('user');
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'count' => $post->comments()->count(),
                'post_id' => $post->id,
                'html' => view('partials.comment', ['comment' => $comment])->render()
            ]);
        }

        return redirect()->back()->with('success', 'Commentaire ajouté avec succès!');
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
