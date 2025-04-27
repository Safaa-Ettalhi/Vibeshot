<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') </title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script> 
    <link href="{{ asset('css/vibeshot.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        .notification-badge {
            box-shadow: 0 0 0 1px #121212; 
        }
    </style>
</head>
<body >
    @auth
    <div class="sidebar ">
    <div class="logo mb-10 pt-6">
        <a href="{{ Auth::user()->is_admin ? route('admin.dashboard') : route('home') }}">
             <img src="{{ asset('images/VibeShot.svg') }}" alt="VibeShot Logo">
        </a>
    </div>
    
    <div class="sidebar-menu mt-4">
                @if(!Auth::user()->is_admin)
                <a href="{{ route('home') }}" class="sidebar-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i data-feather="home"></i>
                    <span>Home</span>
                </a>
                <a href="{{ route('search') }}" class="sidebar-link {{ request()->routeIs('search') ? 'active' : '' }}">
                    <i data-feather="hash"></i>
                    <span>Explore</span>
                </a>
                <a href="{{ route('notifications.index') }}" class="sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <div class="relative inline-block">
                        <i data-feather="bell"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="notification-badge absolute -top-1 -right-1 bg-blue-500 text-white w-4 h-4 flex items-center justify-center rounded-full text-[10px] font-medium">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </div>
                    <span>Notifications</span>
                </a>
                <a href="{{ route('bookmarks.index') }}" class="sidebar-link {{ request()->routeIs('bookmarks.*') ? 'active' : '' }}">
                    <i data-feather="bookmark"></i>
                    <span>Bookmarks</span>
                </a>
                <a href="{{ route('profile.show', auth()->user()->username) }}" class="sidebar-link {{ request()->routeIs('profile.show') && request()->username == auth()->user()->username ? 'active' : '' }}">
                    <i data-feather="user"></i>
                    <span>Profile</span>
                </a>
                <a href="#" class="sidebar-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i data-feather="log-out"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
                @endif 
            </div>
            <a href="{{ route('profile.show', auth()->user()->username) }}">
            <div class="sidebar-profile">
                <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ auth()->user()->name }}" class="sidebar-profile-img ">
                <div>
                    <div class="font-semibold ">{{ auth()->user()->name }}</div>
                    <div class="text-sm text-gray-400">{{ '@' . auth()->user()->username }}</div>
                </div>
            </div></a>
        </div>
        
        <div class="mobile-nav">
        <a href="#" class="sidebar-link mobile-nav-link {{ request()->routeIs('logout') ? 'active' : '' }}" 
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i data-feather="log-out"></i>
</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

            @if(!Auth::user()->is_admin)
            <a href="{{ route('bookmarks.index') }}" class="mobile-nav-link {{ request()->routeIs('bookmarks.*') ? 'active' : '' }}">
               <i data-feather="bookmark"></i>
            </a>
            <a href="{{ route('search') }}" class="mobile-nav-link {{ request()->routeIs('search') ? 'active' : '' }}">
                <i data-feather="search"></i>
            </a>
            <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <i data-feather="home"></i>
            </a>
            <a href="{{ route('notifications.index') }}" class="mobile-nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <div class="relative inline-block">
                    <i data-feather="bell"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="notification-badge absolute -top-1 -right-1 bg-blue-500 text-white w-4 h-4 flex items-center justify-center rounded-full text-[10px] font-medium">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </div>
            </a>
            @endif
            <a href="{{ route('profile.show', auth()->user()->username) }}" class="mobile-nav-link {{ request()->routeIs('profile.show') && request()->username == auth()->user()->username ? 'active' : '' }}">
                <i data-feather="user"></i>
            </a>
        </div>
    @endauth
    
    <div class="{{ auth()->check() ? 'main-content' : '' }} ">
        <!-- Affichage des messages flash -->
        @if(session('success'))
            <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 flash-message">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 flash-message">
                {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace({
                'stroke-width': 1.5
            });
            const dropdownButtons = document.querySelectorAll('.btn-icon');
            dropdownButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const dropdown = this.nextElementSibling;
                    if (dropdown) {
                        dropdown.classList.toggle('hidden');
                    }
                });
            });
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.btn-icon')) {
                    document.querySelectorAll('.absolute').forEach(dropdown => {
                        if (!dropdown.classList.contains('hidden') && !dropdown.classList.contains('notification-badge')) {
                            dropdown.classList.add('hidden');
                        }
                    });
                }
            });

            setTimeout(function() {
                const flashMessages = document.querySelectorAll('.flash-message');
                flashMessages.forEach(message => {
                    message.style.opacity = '0';
                    message.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        message.remove();
                    }, 500);
                });
            }, 3000);
        });
    </script>
</body>
</html>