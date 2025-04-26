<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('user');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('caption', 'like', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                });
        }

        $posts = $query->latest()->get();

        return view('admin.posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $post->load(['user', 'comments.user', 'likes.user', 'images', 'originalPost.user']);

        return view('admin.posts.show', compact('post'));
    }

    public function destroy(Post $post)
    {
        foreach ($post->images as $image) {
            if (!$image->isUsedByOtherPosts()) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }

        if ($post->image_path && !$post->isMainImageUsedByOtherPosts()) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post successfully deleted.');
    }
    public function hide(Post $post)
    {
        $post->is_hidden = true;
        $post->save();

        if (!$post->original_post_id) {
            Post::where('original_post_id', $post->id)->update(['is_hidden' => true]);
        }


        return redirect()->back()->with('success', 'The post has been successfully hidden.');
    }
    public function unhide(Post $post)
    {
        $post->is_hidden = false;
        $post->save();

        if (!$post->original_post_id) {
            Post::where('original_post_id', $post->id)->update(['is_hidden' => false]);
        }

        return redirect()->back()->with('success', 'The post has been successfully made visible.');
    }
}