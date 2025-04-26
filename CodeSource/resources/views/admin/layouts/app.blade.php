<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/vibeshot.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .admin-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
        }
        
        .admin-card {
            background-color: var(--dark-bg);
            border: 1px solid rgba(255, 255, 255, 0.121);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 16px;
        }
        
        .admin-card-header {
            padding: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            background-color: var(--dark-bg);
        }
        
        .admin-card-body {
            padding: 16px;
        }
        
        .admin-card-footer {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .admin-table {
            width: 100%;
            color: var(--text-primary);
        }
        
        .admin-table th {
            color: var(--text-secondary);
            font-weight: 600;
            padding: 12px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .admin-table td {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .admin-table tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .admin-btn {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }
        
        .admin-btn-primary {
            background-color: var(--primary-500);
            color: white;
        }
        
        .admin-btn-secondary {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
        }
        
        .admin-btn-danger {
            background-color: var(--accent-red);
            color: white;
        }
        
        .admin-btn-sm {
            padding: 4px 12px;
            font-size: 0.875rem;
        }
        
        .admin-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .admin-badge-primary {
            background-color: var(--primary-500);
            color: white;
        }
        
        .admin-badge-secondary {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-secondary);
        }
        
        .admin-badge-danger {
            background-color: var(--accent-red);
            color: white;
        }
        
        .admin-form-control {
            width: 100%;
            padding: 10px 16px;
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            transition: all 0.2s;
        }
        
        .admin-form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
        }
        
        .admin-form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-secondary);
        }
        
        .admin-alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        
        .admin-alert-success {
            background-color: rgba(39, 174, 96, 0.2);
            border: 1px solid rgba(39, 174, 96, 0.3);
            color: #2ecc71;
        }
        
        .admin-alert-danger {
            background-color: rgba(231, 76, 60, 0.2);
            border: 1px solid rgba(231, 76, 60, 0.3);
            color: #e74c3c;
        }
        
        .admin-pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .admin-pagination .page-item .page-link {
            background-color: var(--dark-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        .admin-pagination .page-item.active .page-link {
            background-color: var(--primary-500);
            border-color: var(--primary-500);
            color: white;
        }
        
        .admin-pagination .page-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        @media (max-width: 768px) {
            .admin-content {
                margin-left: 0;
                padding-bottom: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
    <div class="logo mb-10 pt-6">
        <a href="{{ Auth::user()->is_admin ? route('admin.dashboard') : route('home') }}">
             <img src="{{ asset('images/VibeShot.svg') }}" alt="VibeShot Logo">
        </a>
    </div>
        
        <div class="sidebar-menu mt-4">
        @if(Auth::user()->is_admin)
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i data-feather="home"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i data-feather="users"></i>
                <span>Users</span>
            </a>
            
            <a href="{{ route('admin.posts.index') }}" class="sidebar-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                <i data-feather="image"></i>
                <span>Posts</span>
            </a>
            
            <a href="{{ route('admin.comments.index') }}" class="sidebar-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                <i data-feather="message-circle"></i>
                <span>Comments</span>
            </a>
            <a href="{{ route('admin.profile.edit') }}" class="sidebar-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <i data-feather="user"></i>
                <span>Mon Profil</span>
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
        <a href="{{ route('admin.profile.edit') }}" class="sidebar-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
        <div class="sidebar-profile">
            <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ auth()->user()->name }}" class="sidebar-profile-img">
            <div>
                <div class="font-semibold text-white">{{ auth()->user()->name }}</div>
                <div class="text-xs text-gray-400">{{ '@' . auth()->user()->username }}</div>
            </div>
        </div></a>
    </div>
    
    <div class="admin-content">
       
        <!-- Affichage des messages flash -->
        @if(session('success'))
            <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 flash-message flex items-center space-x-2 z-50 animate-fade-in-up max-w-[90%] sm:max-w-md">
            <i data-feather="check-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm sm:text-base">  {{ session('success') }}</span>
            </div>
            <script>
        setTimeout(() => {
            document.querySelector('.animate-fade-in-up').classList.add('animate-fade-out-down');
        }, 2000);
    </script>
        @endif
        
        @if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500/90 text-white px-4 py-2 rounded-lg shadow-lg flex items-center space-x-2 z-50 animate-fade-in-up max-w-[90%] sm:max-w-md">
        <i data-feather="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
        <span class="text-sm sm:text-base">{{ session('error') }}</span>
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.animate-fade-in-up').classList.add('animate-fade-out-down');
        }, 2000);
    </script>
@endif
        
       
        
        @yield('content')
    </div>
    
    <div class="mobile-nav">
    @if(Auth::user()->is_admin)
        <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i data-feather="home"></i>
        </a>
        
        <a href="{{ route('admin.users.index') }}" class="mobile-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i data-feather="users"></i>
        </a>
        
        <a href="{{ route('admin.posts.index') }}" class="mobile-nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
            <i data-feather="image"></i>
        </a>
        
        <a href="{{ route('admin.comments.index') }}" class="mobile-nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
            <i data-feather="message-circle"></i>
        </a>
        <a href="{{ route('admin.profile.edit') }}" class="mobile-nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
            <i data-feather="user"></i>
        </a>
        
        <a href="#" class="mobile-nav-link" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
            <i data-feather="log-out"></i>
        </a>
        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        @endif
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
    @yield('scripts')
</body>
</html>