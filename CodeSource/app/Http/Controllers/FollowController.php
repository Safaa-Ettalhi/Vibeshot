<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewFollowerNotification;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function store(User $user)
    {
        if (auth()->id() === $user->id) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas vous suivre vous-même'
                ]);
            }
            return redirect()->back();
        }
        
        if (auth()->user()->following()->where('following_id', $user->id)->exists()) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous suivez déjà cet utilisateur'
                ]);
            }
            return redirect()->back();
        }
        
        auth()->user()->following()->attach($user->id);
        
        $user->notify(new NewFollowerNotification(auth()->user()));
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'following' => true,
                'followers_count' => $user->followers()->count()
            ]);
        }
        
        return redirect()->back();
    }
    
    public function destroy(User $user)
    {
        auth()->user()->following()->detach($user->id);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'following' => false,
                'followers_count' => $user->followers()->count()
            ]);
        }
        
        return redirect()->back();
    }
}
