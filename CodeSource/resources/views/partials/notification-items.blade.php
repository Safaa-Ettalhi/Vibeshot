@foreach($notifications as $notification)
    <div class="notification-item {{ !$notification->read_at ? 'unread' : '' }} hover:bg-gray-800/30 transition-all duration-200" 
         data-notification-id="{{ $notification->id }}">

        <a href="{{ route('profile.show', $notification->data['user_username']) }}" class="notification-avatar group">
            <img 
                src="{{ $notification->data['user_image'] ? asset('storage/' . $notification->data['user_image']) : asset('images/default-avatar.svg') }}" 
                alt="{{ $notification->data['user_name'] }}"
                class="rounded-full object-cover border-2 border-transparent group-hover:border-blue-500 transition-all duration-200"
            >
            @if($notification->data['type'] === 'like')
                <span class="notification-icon-badge bg-red-500">
                    <i data-feather="heart" class="w-3 h-3"></i>
                </span>
            @elseif($notification->data['type'] === 'comment')
                <span class="notification-icon-badge bg-blue-500">
                    <i data-feather="message-circle" class="w-3 h-3"></i>
                </span>
            @elseif($notification->data['type'] === 'follow')
                <span class="notification-icon-badge bg-green-500">
                    <i data-feather="user-plus" class="w-3 h-3"></i>
                </span>
            @endif
        </a>

        <div class="notification-content flex-1 min-w-0">
            <div class="flex items-center flex-wrap gap-1 mb-1">
                <a href="{{ route('profile.show', $notification->data['user_username']) }}" class="font-semibold text-white hover:text-blue-400 transition-colors">
                    {{ $notification->data['user_name'] }}
                </a>
                <span class="text-gray-400 text-sm">{{'@'}}{{ $notification->data['user_username'] }}</span>
            </div>
            
            <p class="text-gray-300 text-sm">
                {{ $notification->data['message'] }}
            </p>
            
            <div class="flex items-center justify-between mt-2">
                <span class="text-xs text-gray-500 flex items-center gap-1">
                    <i data-feather="clock" class="w-3 h-3"></i>
                    {{ $notification->created_at->diffForHumans() }}
                </span>
                
                <div class="flex items-center gap-2">
                    @if($notification->data['type'] === 'like' || $notification->data['type'] === 'comment')
                        <a href="{{ route('posts.show', $notification->data['post_id']) }}" class="notification-btn-glass group">
                            <i data-feather="eye" class="w-4 h-4 group-hover:text-blue-400"></i>
                            <span>View post</span>
                        </a>
                    @elseif($notification->data['type'] === 'follow')
                        <a href="{{ route('profile.show', $notification->data['user_username']) }}" class="notification-btn-primary">
                            <i data-feather="user" class="w-4 h-4"></i>
                            <span>View profile</span>
                        </a>
                    @endif
                    
                    @if(!$notification->read_at)
                        <form class="mark-read-form" action="{{ route('notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="notification-btn-glass text-gray-400 hover:text-white">
                                <i data-feather="check" class="w-4 h-4"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        @if(isset($notification->data['post_image']) && ($notification->data['type'] === 'like' || $notification->data['type'] === 'comment'))
            <a href="{{ route('posts.show', $notification->data['post_id']) }}" class="notification-thumbnail group">
                <img 
                    src="{{ asset('storage/' . $notification->data['post_image']) }}" 
                    alt="Post thumbnail"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                >
            </a>
        @endif
    </div>
@endforeach