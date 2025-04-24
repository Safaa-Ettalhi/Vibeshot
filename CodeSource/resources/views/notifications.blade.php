@extends('layouts.app')

@section('content')
<div class="container bg-black min-h-screen">
    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <span>Notifications</span>
                    @if($notifications->count() > 0)
                        <span class="px-3 py-1 text-sm bg-blue-500/20 text-blue-400 rounded-full">
                            {{ $notifications->count() }} new
                        </span>
                    @endif
                </h1>
               
            </div>
            
            @if($notifications->count() > 0)
                <form action="{{ route('notifications.read.all') }}" method="POST">
                    @csrf
                    <button type="submit" class="group flex items-center gap-2 px-4 py-2 bg-gray-800/50 hover:bg-gray-700/50 text-white rounded-full transition-all duration-200 border border-gray-700 hover:border-blue-500">
                        <i data-feather="check-circle" class="w-4 h-4 group-hover:text-blue-400"></i>
                        <span class="font-medium">Mark all as read</span>
                    </button>
                </form>
            @endif
        </div>
        

        <div class="bg-gray-900/50 rounded-xl overflow-hidden border border-gray-800 shadow-lg backdrop-blur-sm">
            @if($notifications->count() > 0)
                <div class="border-b border-gray-800/50 bg-gray-900/30">
                    <div class="flex overflow-x-auto scrollbar-hide">
                        <button class="notification-tab active" data-type="all">
                            <i data-feather="bell" class="w-4 h-4 mr-2"></i>
                            All Notifications
                        </button>
                        <button class="notification-tab" data-type="like">
                            <i data-feather="heart" class="w-4 h-4 mr-2"></i>
                            Likes
                        </button>
                        <button class="notification-tab" data-type="comment">
                            <i data-feather="message-circle" class="w-4 h-4 mr-2"></i>
                            Comments
                        </button>
                        <button class="notification-tab" data-type="follow">
                            <i data-feather="user-plus" class="w-4 h-4 mr-2"></i>
                            Follows
                        </button>
                    </div>
                </div>
                
              
                <div id="notifications-list" class="divide-y divide-gray-800/50">
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
                </div>  
            @else
              
                <div class="py-16 px-4 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-800/50 mb-6">
                        <i data-feather="bell" class="w-8 h-8  text-blue-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">No notifications yet</h3>
                    <p class="text-gray-400 max-w-sm mx-auto">
                        When someone likes, comments, or follows you, you'll see it here.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>

.notification-tab {
    @apply flex items-center px-6 py-4 text-gray-400 font-medium relative transition-all duration-200;
    @apply hover:text-white hover:bg-gray-800/50 cursor-pointer border-b-2 border-transparent;
}

.notification-tab.active {
    @apply text-blue-500 border-blue-500;
}

.notification-item {
    @apply flex items-start gap-4 p-4;
}

.notification-item.unread {
    @apply bg-blue-500/5 border-l-2 border-blue-500;
}

.notification-avatar {
    @apply relative flex-shrink-0;
}

.notification-avatar img {
    @apply w-12 h-12;
}

.notification-icon-badge {
    @apply absolute -bottom-1 -right-1 w-6 h-6 rounded-full flex items-center justify-center text-white;
}

.notification-thumbnail {
    @apply w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 border border-gray-700;
}

.notification-btn-glass {
    @apply inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-gray-300 bg-gray-800/50 
           hover:bg-gray-700/50 rounded-lg transition-all duration-200 border border-gray-700;
}

.notification-btn-primary {
    @apply inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-white bg-blue-500 
           hover:bg-blue-600 rounded-lg transition-all duration-200;
}

.notification-loader {
    @apply animate-pulse bg-gray-800/30 h-20 mb-2 rounded-lg;
}

.notification-tabs-container {
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
}

.notification-tab {
    scroll-snap-align: start;
}

@keyframes notification-enter {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.notification-item.new {
    animation: notification-enter 0.3s ease-out;
}

.notification-item:hover .notification-actions {
    opacity: 1;
}

@media (max-width: 640px) {
    .notification-item {
        @apply flex-col;
    }
    
    .notification-thumbnail {
        @apply w-full h-40 mt-3;
    }
    
    .notification-content {
        @apply w-full;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
    
    const tabs = document.querySelectorAll('.notification-tab');
    const notificationsList = document.getElementById('notifications-list');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const type = this.getAttribute('data-type');

            notificationsList.innerHTML = `
                <div class="notification-loader"></div>
                <div class="notification-loader"></div>
                <div class="notification-loader"></div>
            `;

            fetch(`/notifications/filter/${type}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                notificationsList.innerHTML = data.html;
                feather.replace();
                initializeNotificationHandlers();
            })
            .catch(error => {
                console.error('Error:', error);
                notificationsList.innerHTML = `
                    <div class="p-8 text-center text-red-500">
                        <p>Failed to load notifications. Please try again.</p>
                    </div>
                `;
            });
        });
    });

    function initializeNotificationHandlers() {
        
        document.querySelectorAll('.mark-read-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const notificationItem = this.closest('.notification-item');
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notificationItem.classList.remove('unread');
                        this.remove();
                        updateNotificationCounter();
                    }
                });
            });
        });
    }

    function updateNotificationCounter() {
        fetch('{{ route("notifications.count") }}')
            .then(response => response.json())
            .then(data => {
                const counters = document.querySelectorAll('.notification-counter');
                counters.forEach(counter => {
                    if (data.count > 0) {
                        counter.textContent = data.count;
                        counter.classList.remove('hidden');
                    } else {
                        counter.classList.add('hidden');
                    }
                });
            });
    }
    let page = 1;
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                loadMoreNotifications();
            }
        });
    }, {
        rootMargin: '100px'
    });
    const lastNotification = document.querySelector('.notification-item:last-child');
    if (lastNotification) {
        observer.observe(lastNotification);
    }
    
    function loadMoreNotifications() {
        page++;
        const activeTab = document.querySelector('.notification-tab.active');
        const type = activeTab.getAttribute('data-type');
        
        fetch(`/notifications/filter/${type}?page=${page}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                notificationsList.insertAdjacentHTML('beforeend', data.html);
                feather.replace();
                initializeNotificationHandlers();

                const newLastItem = document.querySelector('.notification-item:last-child');
                if (newLastItem) {
                    observer.observe(newLastItem);
                }
            }
        });
    }

    initializeNotificationHandlers();
    
});
</script>
@endsection