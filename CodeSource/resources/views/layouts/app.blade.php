<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'VibeShot') }}</title>

    <!-- Fonts -->
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com"> -->
    <!-- <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/vibeshot.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Scripts -->
    <script src="https://unpkg.com/feather-icons"></script>
    
</head>
<body class="">
    @auth
    <div class="sidebar ">
    <div class="logo mb-10 pt-6">
        <a href="{{ route('home') }}">
             <img src="{{ asset('images/VibeShot.svg') }}" alt="VibeShot Logo">
        </a>
    </div>
    
    <div class="sidebar-menu mt-4">
                <a href="{{ route('home') }}" class="sidebar-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i data-feather="home"></i>
                    <span>Home</span>
                </a>
                <a href="{{ route('search') }}" class="sidebar-link {{ request()->routeIs('search') ? 'active' : '' }}">
                    <i data-feather="hash"></i>
                    <span>Explore</span>
                </a>
                <a href="{{ route('notifications.index') }}" class="sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <i data-feather="bell"></i>
                    <span>Notifications</span>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="ml-auto bg-primary-500 text-white px-2 py-1 rounded-full text-xs">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
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

            <a href="{{ route('bookmarks.index') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
               <i data-feather="bookmark"></i>
            </a>
            <a href="{{ route('search') }}" class="mobile-nav-link {{ request()->routeIs('search') ? 'active' : '' }}">
                <i data-feather="search"></i>
            </a>
            <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('post.create') ? 'active' : '' }}">
                <i data-feather="home"></i>
            </a>
            <a href="{{ route('notifications.index') }}" class="mobile-nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i data-feather="bell"></i>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute top-0 right-0 bg-primary-500 text-white px-1 rounded-full text-xs">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>
            <a href="{{ route('profile.show', auth()->user()->username) }}" class="mobile-nav-link {{ request()->routeIs('profile.show') && request()->username == auth()->user()->username ? 'active' : '' }}">
                <i data-feather="user"></i>
            </a>
        </div>
    @endauth
    
    <div class="{{ auth()->check() ? 'main-content' : '' }} ">
        @yield('content')
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace({
                'stroke-width': 1.5 // Réduit l'épaisseur des icônes pour correspondre aux maquettes
            });
            
            // Toggle dropdown menus
            const dropdownButtons = document.querySelectorAll('.btn-icon');
            dropdownButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const dropdown = this.nextElementSibling;
                    if (dropdown) {
                        dropdown.classList.toggle('hidden');
                    }
                });
            });
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.btn-icon')) {
                    document.querySelectorAll('.absolute').forEach(dropdown => {
                        if (!dropdown.classList.contains('hidden')) {
                            dropdown.classList.add('hidden');
                        }
                    });
                }
            });
            
            // Navigation entre les images dans les posts
            const imageContainers = document.querySelectorAll('.post-image-container');
            imageContainers.forEach(container => {
                if (container.children.length > 1) {
                    // Créer les boutons de navigation
                    const prevBtn = document.createElement('button');
                    prevBtn.className = 'absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 rounded-full p-2';
                    prevBtn.innerHTML = '<i data-feather="chevron-left" class="w-5 h-5 text-white"></i>';
                    
                    const nextBtn = document.createElement('button');
                    nextBtn.className = 'absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 rounded-full p-2';
                    nextBtn.innerHTML = '<i data-feather="chevron-right" class="w-5 h-5 text-white"></i>';
                    
                    // Ajouter les boutons au conteneur
                    container.style.position = 'relative';
                    container.appendChild(prevBtn);
                    container.appendChild(nextBtn);
                    
                    // Initialiser les indicateurs
                    const indicators = document.createElement('div');
                    indicators.className = 'absolute bottom-2 left-0 right-0 flex justify-center gap-1';
                    
                    for (let i = 0; i < container.children.length; i++) {
                        if (container.children[i].tagName === 'IMG') {
                            const dot = document.createElement('div');
                            dot.className = `w-2 h-2 rounded-full ${i === 0 ? 'bg-blue-500' : 'bg-gray-500'}`;
                            indicators.appendChild(dot);
                        }
                    }
                    
                    container.appendChild(indicators);
                    
                    // Mettre à jour les icônes
                    feather.replace();
                    
                    // Gérer la navigation
                    let currentIndex = 0;
                    
                    prevBtn.addEventListener('click', function() {
                        if (currentIndex > 0) {
                            currentIndex--;
                            scrollToImage();
                            updateIndicators();
                        }
                    });
                    
                    nextBtn.addEventListener('click', function() {
                        if (currentIndex < container.children.length - 3) { // -3 pour les 2 boutons et les indicateurs
                            currentIndex++;
                            scrollToImage();
                            updateIndicators();
                        }
                    });
                    
                    function scrollToImage() {
                        const images = Array.from(container.children).filter(child => child.tagName === 'IMG');
                        if (images[currentIndex]) {
                            images[currentIndex].scrollIntoView({ behavior: 'smooth', inline: 'start' });
                        }
                    }
                    
                    function updateIndicators() {
                        const dots = indicators.children;
                        for (let i = 0; i < dots.length; i++) {
                            dots[i].className = `w-2 h-2 rounded-full ${i === currentIndex ? 'bg-blue-500' : 'bg-gray-500'}`;
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>