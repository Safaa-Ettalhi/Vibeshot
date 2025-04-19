@extends('layouts.app')

@section('content')
<div class="container">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Notifications</h1>
            
            <form action="{{ route('notifications.read.all') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary">Mark all as read</button>
            </form>
        </div>
        
        <div class="card">
            <div class="card-body">
                @if($notifications->count() > 0)
                    @foreach($notifications as $notification)
                        <div class="notification {{ $notification->read_at ? '' : 'notification-unread' }} mb-3">
                            <img src="{{ asset('storage/' . $notification->data['user_image'] ?? 'images/default-avatar.svg') }}" alt="{{ $notification->data['user_name'] }}" class="avatar w-10 h-10">
                            
                            <div class="notification-content">
                                <div>
                                    <a href="{{ route('profile.show', $notification->data['user_username']) }}" class="font-semibold">{{ $notification->data['user_name'] }}</a>
                                    {{ $notification->data['message'] }}
                                    
                                    @if($notification->data['type'] === 'like')
                                        <a href="{{ route('posts.show', $notification->data['post_id']) }}" class="text-blue-500">View post</a>
                                    @elseif($notification->data['type'] === 'comment')
                                        <a href="{{ route('posts.show', $notification->data['post_id']) }}" class="text-blue-500">View comment</a>
                                    @elseif($notification->data['type'] === 'follow')
                                        <a href="{{ route('profile.show', $notification->data['user_username']) }}" class="text-blue-500">View profile</a>
                                    @endif
                                </div>
                                
                                <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                            </div>
                            
                            @if(!$notification->read_at)
                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-icon">
                                        <i data-feather="check"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                    
                    {{ $notifications->links() }}
                @else
                    <div class="text-center py-6">
                        <div class="text-6xl mb-4">🔔</div>
                        <h3 class="text-xl font-semibold mb-2">No notifications yet</h3>
                        <p class="text-gray-400">When you get notifications, they'll show up here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection