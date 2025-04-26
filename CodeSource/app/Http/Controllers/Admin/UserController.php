<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $users = $query->latest()->get();
        
        return view('admin.users.index', compact('users'));
    }
    
    public function show(User $user)
    {
        $user->load(['posts', 'comments', 'followers', 'following']);
        
        return view('admin.users.show', compact('user'));
    } 
    
    public function destroy(User $user)
    {
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }
        
        if ($user->cover_image) {
            Storage::disk('public')->delete($user->cover_image);
        }

        foreach ($user->posts as $post) {
            foreach ($post->images as $image) {
                if (!$image->isUsedByOtherPosts()) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }
            
            if ($post->image_path && !$post->isMainImageUsedByOtherPosts()) {
                Storage::disk('public')->delete($post->image_path);
            }
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'User successfully deleted.');
    }
    
    public function toggleAdmin(User $user)
    {
        $user->is_admin = !$user->is_admin;
        $user->save();
        
        $status = $user->is_admin ? 'administrator' : 'normal user';
        
        return redirect()->back()->with('success', "{$user->name}'s status has been changed to {$status}.");
    }

    public function block(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot block yourself.');
        }

        $user->is_blocked = true;
        $user->save();

        return redirect()->back()->with('success', "User {$user->name} has been successfully blocked.");
    }

    public function unblock(User $user)
    {
        $user->is_blocked = false;
        $user->save();

        return redirect()->back()->with('success', "User {$user->name} has been successfully unblocked.");
    }
}