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
        
        $posts = $query->latest()->paginate(10);
        
        return view('admin.posts.index', compact('posts'));
    }
    
    public function show(Post $post)
    {
        $post->load(['user', 'comments.user', 'likes.user', 'images', 'originalPost.user']);
        
        return view('admin.posts.show', compact('post'));
    }
    
    public function edit(Post $post)
    {
        $post->load(['images']);
        
        return view('admin.posts.edit', compact('post'));
    }
    
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'caption' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
        
        $post->caption = $request->caption;
        $post->save();
        
        if ($request->hasFile('images')) {
            $order = $post->images->count();
            
            foreach ($request->file('images') as $image) {
                $path = $image->store('posts', 'public');
                
                $post->images()->create([
                    'image_path' => $path,
                    'order' => $order++
                ]);
            }
        }
        
        return redirect()->route('admin.posts.index')->with('success', 'Publication mise à jour avec succès.');
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
        
        return redirect()->route('admin.posts.index')->with('success', 'Publication supprimée avec succès.');
    }
    public function hide(Post $post){
    $post->is_hidden = true;
    $post->save();
 
    if (!$post->original_post_id) {
        Post::where('original_post_id', $post->id)->update(['is_hidden' => true]);
    }
    
    
    return redirect()->back()->with('success', 'La publication a été masquée avec succès.');
}
public function unhide(Post $post)
{
    $post->is_hidden = false;
    $post->save();

    if (!$post->original_post_id) {
        Post::where('original_post_id', $post->id)->update(['is_hidden' => false]);
     }
    
    return redirect()->back()->with('success', 'La publication a été rendue visible avec succès.');
}
}