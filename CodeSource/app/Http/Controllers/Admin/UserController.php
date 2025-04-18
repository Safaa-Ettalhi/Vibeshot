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
        
        $users = $query->latest()->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }
    
    public function show(User $user)
    {
        $user->load(['posts', 'comments', 'followers', 'following']);
        
        return view('admin.users.show', compact('user'));
    }
    
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $userData = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'bio' => $request->bio,
        ];
        
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $userData['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }
        
        if ($request->hasFile('cover_image')) {
            if ($user->cover_image) {
                Storage::disk('public')->delete($user->cover_image);
            }
            $userData['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }
        
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        
        $user->update($userData);
        
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès.');
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
        
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
    
    public function toggleAdmin(User $user)
    {
        $user->is_admin = !$user->is_admin;
        $user->save();
        
        $status = $user->is_admin ? 'administrateur' : 'utilisateur normal';
        
        return redirect()->back()->with('success', "Le statut de {$user->name} a été changé en {$status}.");
    }

    public function block(User $user)
{

    if ($user->id === auth()->id()) {
        return redirect()->back()->with('error', 'Vous ne pouvez pas vous bloquer vous-même.');
    }

    $user->is_blocked = true;
    $user->save();

    return redirect()->back()->with('success', "L'utilisateur {$user->name} a été bloqué avec succès.");
}

public function unblock(User $user)
{
    $user->is_blocked = false;
    $user->save();

    return redirect()->back()->with('success', "L'utilisateur {$user->name} a été débloqué avec succès.");
}
}