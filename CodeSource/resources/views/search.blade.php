@extends('layouts.app')

@section('content')
<div class="container bg-black">
    <div class="w-full max-w-5xl mx-auto p-6">
        <div class="mb-8">
            <form action="{{ route('search') }}" method="GET">
                <div class="relative flex items-center">
                    <input 
                        type="text" 
                        name="query" 
                        class="w-full bg-white/10 border border-white/20 rounded-full py-4 px-6 text-white focus:outline-none focus:border-blue-500 focus:bg-white/15 focus:ring-2 focus:ring-blue-500/20 transition-all duration-300" 
                        placeholder="Search for people or posts..." 
                        value="{{ $query ?? '' }}"
                    >
                    <i data-feather="search" class="absolute right-5 text-gray-400"></i>
                </div>
            </form>
        </div>

        @if(isset($query))
            <div class="flex flex-col gap-6">
                <!-- Users Results Section -->
                <div class="bg-gray-900/50 rounded-2xl overflow-hidden border border-white/10 transition-all duration-300 hover:shadow-lg hover:shadow-black/50 mb-8">
                    <div class="p-5 border-b border-white/10 flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-white relative pl-3 before:content-[''] before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-3/5 before:bg-gradient-to-b before:from-blue-500 before:to-indigo-600 before:rounded-md">People</h2>
                    </div>
                    
                    <div class="p-2">
                        @if($users->count() > 0)
                            <div class="flex flex-col">
                                @foreach($users as $user)
                                    <div class="flex items-center justify-between p-3.5 rounded-xl transition-colors duration-200 hover:bg-white/5 mb-1">
                                        <a href="{{ route('profile.show', $user->username) }}" class="flex items-center gap-4 flex-1">
                                            <img 
                                                src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.svg') }}" 
                                                alt="{{ $user->name }}" 
                                                class="w-12 h-12 rounded-full object-cover border-2 border-transparent transition-all hover:border-blue-500"
                                            >
                                            <div class="flex flex-col">
                                                <div class="font-semibold text-white">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-400">{{ '@' . $user->username }}</div>
                                            </div>
                                        </a>
                                        
                                        @if($user->id !== auth()->id())
                                            <div>
                                                @if(auth()->user()->isFollowing($user))
                                                    <form action="{{ route('follow.destroy', $user) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-transparent border border-white/20 text-white rounded-full px-5 py-2 text-sm font-medium transition-all hover:bg-white/10 transform hover:-translate-y-0.5">Unfollow</button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('follow.store', $user) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="bg-blue-500 text-white rounded-full px-5 py-2 text-sm font-medium transition-all hover:bg-blue-600 transform hover:-translate-y-0.5">Follow</button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 flex flex-col items-center">
                                <p class="text-gray-400">No users found for "{{ $query }}"</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Posts Results Section -->
                <div class="bg-gray-900/50 rounded-2xl overflow-hidden border border-white/10 transition-all duration-300 hover:shadow-lg hover:shadow-black/50">
                    <div class="p-5 border-b border-white/10 flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-white relative pl-3 before:content-[''] before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-3/5 before:bg-gradient-to-b before:from-blue-500 before:to-indigo-600 before:rounded-md">Posts</h2>
                        <div class="flex items-center gap-2">
                            <button class="view-toggle bg-transparent border-none w-9 h-9 rounded-lg flex items-center justify-center text-gray-400 cursor-pointer transition-all hover:bg-white/10 hover:text-white" data-view="grid">
                                <i data-feather="grid" class="w-4.5 h-4.5"></i>
                            </button>
                            <button class="view-toggle active bg-white/10 border-none w-9 h-9 rounded-lg flex items-center justify-center text-blue-500 cursor-pointer transition-all hover:bg-white/15" data-view="masonry">
                                <i data-feather="layout" class="w-4.5 h-4.5"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-2">
                        @if($posts->count() > 0)
                            <div class="posts-masonry columns-1 sm:columns-2 lg:columns-3 gap-5 p-5 space-y-5" id="postsContainer">
                                @foreach($posts as $post)
                                    <div class="post-item bg-gray-800/70 backdrop-blur-md rounded-2xl overflow-hidden border border-white/5 mb-5 break-inside-avoid transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-black/30 hover:border-blue-500/30 relative">
                                        <div class="flex items-center justify-between p-4 border-b border-white/5">
                                            <a href="{{ route('profile.show', $post->user->username) }}" class="flex items-center gap-3 no-underline">
                                                <img 
                                                    src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : asset('images/default-avatar.svg') }}" 
                                                    alt="{{ $post->user->name }}" 
                                                    class="w-10 h-10 rounded-full object-cover border-2 border-transparent transition-all group-hover:border-blue-500 bg-black"
                                                >
                                                <div class="flex flex-col">
                                                    <div class="font-semibold text-white leading-tight">{{ $post->user->name }}</div>
                                                    <div class="text-xs text-gray-400">{{ '@' . $post->user->username }}</div>
                                                </div>
                                            </a>
                                            <div class="flex items-center gap-1.5 text-gray-400 text-xs">
                                                <i data-feather="clock" class="w-3.5 h-3.5 opacity-70"></i>
                                                <span>{{ $post->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        
                                        <a href="{{ route('posts.show', $post) }}" class="relative block w-full overflow-hidden group">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 flex items-end justify-center pb-4 transition-opacity duration-300 z-10">
                                                <div class="flex gap-5 text-white">
                                                    <div class="flex items-center gap-1.5 font-semibold text-sm">
                                                        <i data-feather="heart" class="w-4.5 h-4.5"></i>
                                                        <span>{{ $post->likes_count ?? rand(5, 120) }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5 font-semibold text-sm">
                                                        <i data-feather="message-circle" class="w-4.5 h-4.5"></i>
                                                        <span>{{ $post->comments_count ?? rand(0, 30) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <img 
                                                src="{{ asset('storage/' . $post->image_path) }}" 
                                                alt="Post by {{ $post->user->name }}" 
                                                class="w-full transition-transform duration-500 group-hover:scale-105"
                                            >
                                            
                                            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black/80 opacity-70 transition-opacity duration-300 group-hover:opacity-90 z-0"></div>
                                        </a>
                                        
                                        <div class="p-5 relative z-10">
                                            @if($post->caption)
                                                <div class="text-sm text-gray-100 leading-relaxed mb-5">
                                                    <span class="font-semibold text-white mr-1.5">{{ $post->user->name }}</span>
                                                    {{ Str::limit($post->caption, 100) }}
                                                </div>
                                            @endif
                                            
                                            <div class="flex justify-between items-center py-3 border-t border-b border-white/5 mb-4">
                                                <button class="post-action bg-transparent border-none flex items-center justify-center text-gray-400 w-9 h-9 rounded-full transition-all hover:bg-white/10 hover:text-white">
                                                    <i data-feather="heart" class="w-4.5 h-4.5 transition-transform hover:scale-125"></i>
                                                </button>
                                                <button class="post-action bg-transparent border-none flex items-center justify-center text-gray-400 w-9 h-9 rounded-full transition-all hover:bg-white/10 hover:text-white">
                                                    <i data-feather="message-circle" class="w-4.5 h-4.5 transition-transform hover:scale-125"></i>
                                                </button>
                                                <button class="post-action bg-transparent border-none flex items-center justify-center text-gray-400 w-9 h-9 rounded-full transition-all hover:bg-white/10 hover:text-white">
                                                    <i data-feather="bookmark" class="w-4.5 h-4.5 transition-transform hover:scale-125"></i>
                                                </button>
                                                <button class="post-action bg-transparent border-none flex items-center justify-center text-gray-400 w-9 h-9 rounded-full transition-all hover:bg-white/10 hover:text-white">
                                                    <i data-feather="share" class="w-4.5 h-4.5 transition-transform hover:scale-125"></i>
                                                </button>
                                            </div>
                                            
                                            <a href="{{ route('posts.show', $post) }}" class="block text-center py-3 bg-blue-500/10 text-blue-500 rounded-lg font-medium transition-all hover:bg-blue-500/20 transform hover:-translate-y-0.5 no-underline">
                                                View post
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 flex flex-col items-center justify-center">
                                <div class="text-6xl mb-4 bg-gradient-to-r from-blue-500 to-indigo-600 bg-clip-text text-transparent animate-bounce">📷</div>
                                <p class="text-lg text-white mb-2 font-medium">No posts found for "{{ $query }}"</p>
                                <p class="text-gray-400 max-w-xs leading-relaxed">Try searching for something else or explore trending posts</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 my-8 bg-white/5 rounded-2xl border border-dashed border-white/10">
                <div class="text-6xl mb-6 animate-pulse">🔍</div>
                <h3 class="text-2xl font-semibold text-white mb-3">Search for people and posts</h3>
                <p class="text-gray-400 max-w-md text-center">Enter a search term to find users or posts.</p>
            </div>
        @endif
    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function() {
    
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    
    const viewToggles = document.querySelectorAll('.view-toggle');
    const postsContainer = document.getElementById('postsContainer');
    
    if (viewToggles.length && postsContainer) {
        viewToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
               
                viewToggles.forEach(btn => {
                    btn.classList.remove('active', 'bg-white/10', 'text-blue-500');
                    btn.classList.add('text-gray-400', 'bg-transparent');
                });
                this.classList.add('active', 'bg-white/10', 'text-blue-500');
                this.classList.remove('text-gray-400', 'bg-transparent');
                
                
                const view = this.getAttribute('data-view');
                if (view === 'grid') {
                    postsContainer.classList.remove('posts-masonry', 'columns-1', 'sm:columns-2', 'lg:columns-3');
                    postsContainer.classList.add('grid', 'grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3', 'gap-5');
                } else {
                    postsContainer.classList.remove('grid', 'grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3');
                    postsContainer.classList.add('posts-masonry', 'columns-1', 'sm:columns-2', 'lg:columns-3');
                }
                
              
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });
        });
    }
    
    
    const postItems = document.querySelectorAll('.post-item');
    if (postItems.length) {
       
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 150);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        
        postItems.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(item);
        });
    }
});
</script>
@endsection