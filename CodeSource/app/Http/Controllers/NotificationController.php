<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
 public function index(Request $request)
    {
        $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->paginate(10);
        
        
        if ($request->ajax()) {
            $view = view('partials.notification-items', compact('notifications'))->render();
            return response()->json(['html' => $view]);
        }
        
        return view('notifications', compact('notifications'));
    }
    
    public function markAsRead($id, Request $request)
    {
        
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
                'unread_count' => auth()->user()->unreadNotifications()->count()
            ]);
        }

        return redirect()->back()->with('success', 'Notification marked as read');
    }
  
    public function markAllAsRead(Request $request)
    {
        
        auth()->user()->unreadNotifications->markAsRead();
        
       
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'unread_count' => 0
            ]);
        }
        
        
        return redirect()->back()->with('success', 'All notifications marked as read');
    }
    
    public function getUnreadCount()
    {
        $count = auth()->user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }

    public function filterByType($type, Request $request)
{
    $validTypes = ['like', 'comment', 'follow', 'all'];
    if (!in_array($type, $validTypes)) {
        return abort(404);
    }
    $query = auth()->user()->notifications();

    if ($type !== 'all') {
       
        $query->whereJsonContains('data->type', $type);
    }

    $notifications = $query->orderBy('created_at', 'desc')->paginate(10);

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'html' => view('partials.notification-items', compact('notifications'))->render(),
            'count' => $notifications->count()
        ]);
    }

    return view('notifications', compact('notifications', 'type'));
}
      
 
}