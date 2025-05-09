<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'post']);
        
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                  });
        }
        
        $comments = $query->latest()->get();
        
        return view('admin.comments.index', compact('comments'));
    }
    
    public function show(Comment $comment)
    {
        $comment->load(['user', 'post.user']);
        
        return view('admin.comments.show', compact('comment'));
    }
    
    public function destroy(Comment $comment)
    {
        $comment->delete();
        
        return redirect()->route('admin.comments.index')->with('success', 'Comment successfully deleted.');
    }
}