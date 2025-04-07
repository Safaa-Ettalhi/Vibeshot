<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{

    
    public function show($username)
    {
        $user = User::where('username', $username)
            ->with(['posts' => function ($query) {
                $query->latest()->with(['likes', 'comments', 'user']);
            }])
            ->firstOrFail();
            
        $postsCount = $user->posts()->count();
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();
        $isFollowing = auth()->check() ? auth()->user()->isFollowing($user) : false;
        
        return view('profile.show', compact('user', 'postsCount', 'followersCount', 'followingCount', 'isFollowing'));
    }
    
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }
    
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:5120',
        ]);
        
        if ($request->hasFile('profile_image')) {
            
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $profileImagePath = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $profileImagePath;
        }
        
        if ($request->hasFile('cover_image')) {
           
            if ($user->cover_image && Storage::disk('public')->exists($user->cover_image)) {
                Storage::disk('public')->delete($user->cover_image);
            }
            
            $coverImagePath = $request->file('cover_image')->store('covers', 'public');
            $user->cover_image = $coverImagePath;
        }
        
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->bio = $request->bio;
        $user->save();
        
        return redirect()->route('profile.show', $user->username)
            ->with('success', 'Profil mis à jour avec succès!');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = auth()->user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->route('profile.edit')
            ->with('success', 'Mot de passe mis à jour avec succès!');
    }
}