<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        
        $notifications = auth()->user()->notifications()->paginate(20);
        
        return view('notifications', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back();
    }
    
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back();
    }
    
    public function getUnreadCount()
    {
        $count = auth()->user()->unreadNotifications()->count();
        
        return response()->json(['count' => $count]);
    }
}
