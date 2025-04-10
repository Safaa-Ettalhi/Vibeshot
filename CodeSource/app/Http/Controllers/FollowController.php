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
            return redirect()->back();
        }
        
      
        if (auth()->user()->following()->where('following_id', $user->id)->exists()) {
            return redirect()->back();
        }
        
        auth()->user()->following()->attach($user->id);
        
      
        $user->notify(new NewFollowerNotification(auth()->user()));
        
        return redirect()->back();
    }
    
    public function destroy(User $user)
    {
        auth()->user()->following()->detach($user->id);
        
        return redirect()->back();
    }
}