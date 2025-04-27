@extends('layouts.app')

@section('title', 'Trending posts')
@section('content')
<div class="container bg-black min-h-screen">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-900/40 backdrop-blur-xl border border-gray-800/50 rounded-2xl p-6 mb-8 shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-r from-red-500 to-pink-500 p-2 rounded-lg shadow-md">
                            <i class="ri-trending-up-line text-xl text-white"></i>
                        </div>
                        <h1 class="text-3xl font-bold text-white tracking-tight">Trending Posts</h1>
                    </div>
                    
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-blue-400 hover:text-blue-300 transition-colors bg-gray-800/50 hover:bg-gray-800 px-4 py-2 rounded-full">
                        <i class="ri-arrow-left-line"></i>
                        Back to home
                    </a>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-gray-900/70 to-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-800/50 p-5 mb-8 shadow-lg">
            <div class="flex flex-col lg:flex-row gap-6 justify-between">
                <div class="space-y-3">
                    <h3 class="text-white font-medium flex items-center gap-2">
                        <i class="ri-calendar-line text-blue-400"></i>
                        Time Period
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('trending.index', ['period' => 'today', 'type' => $type]) }}" 
                           class="px-4 py-2 rounded-full text-sm font-medium transition-all transform hover:-translate-y-0.5 {{ $period === 'today' ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md' : 'bg-gray-800/70 text-gray-300 hover:bg-gray-700/70' }}">
                            Today 
                            <span class="inline-flex items-center justify-center ml-1.5 w-5 h-5 text-xs {{ $period === 'today' ? 'bg-blue-700/50 text-white' : 'bg-gray-700/70 text-gray-300' }} rounded-full">{{ $stats['today'] }}</span>
                        </a>
                        <a href="{{ route('trending.index', ['period' => 'week', 'type' => $type]) }}" 
                           class="px-4 py-2 rounded-full text-sm font-medium transition-all transform hover:-translate-y-0.5 {{ $period === 'week' ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md' : 'bg-gray-800/70 text-gray-300 hover:bg-gray-700/70' }}">
                            This week
                            <span class="inline-flex items-center justify-center ml-1.5 w-5 h-5 text-xs {{ $period === 'week' ? 'bg-blue-700/50 text-white' : 'bg-gray-700/70 text-gray-300' }} rounded-full">{{ $stats['week'] }}</span>
                        </a>
                        <a href="{{ route('trending.index', ['period' => 'month', 'type' => $type]) }}" 
                           class="px-4 py-2 rounded-full text-sm font-medium transition-all transform hover:-translate-y-0.5 {{ $period === 'month' ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md' : 'bg-gray-800/70 text-gray-300 hover:bg-gray-700/70' }}">
                            This month
                            <span class="inline-flex items-center justify-center ml-1.5 w-5 h-5 text-xs {{ $period === 'month' ? 'bg-blue-700/50 text-white' : 'bg-gray-700/70 text-gray-300' }} rounded-full">{{ $stats['month'] }}</span>
                        </a>
                        <a href="{{ route('trending.index', ['period' => 'year', 'type' => $type]) }}" 
                           class="px-4 py-2 rounded-full text-sm font-medium transition-all transform hover:-translate-y-0.5 {{ $period === 'year' ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md' : 'bg-gray-800/70 text-gray-300 hover:bg-gray-700/70' }}">
                            This year
                            <span class="inline-flex items-center justify-center ml-1.5 w-5 h-5 text-xs {{ $period === 'year' ? 'bg-blue-700/50 text-white' : 'bg-gray-700/70 text-gray-300' }} rounded-full">{{ $stats['year'] }}</span>
                        </a>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="space-y-3">
                        <h3 class="text-white font-medium flex items-center gap-2">
                            <i class="ri-filter-line text-blue-400"></i>
                            Content Type
                        </h3>
                        <div class="flex gap-2">
                            <a href="{{ route('trending.index', ['period' => $period, 'type' => 'all']) }}" 
                               class="px-4 py-2 rounded-full text-sm font-medium transition-all transform hover:-translate-y-0.5 {{ $type === 'all' ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md' : 'bg-gray-800/70 text-gray-300 hover:bg-gray-700/70' }}">
                                All content
                            </a>
                            <a href="{{ route('trending.index', ['period' => $period, 'type' => 'images']) }}" 
                               class="px-4 py-2 rounded-full text-sm font-medium transition-all transform hover:-translate-y-0.5 {{ $type === 'images' ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md' : 'bg-gray-800/70 text-gray-300 hover:bg-gray-700/70' }}">
                                Images only
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex items-center justify-between mb-6 bg-gray-900/30 backdrop-blur-sm rounded-xl px-4 py-3 border border-gray-800/30">
            <div class="flex items-center gap-2">
                <div class="bg-blue-500/20 text-blue-400 p-1.5 rounded-lg">
                    <i class="ri-search-line"></i>
                </div>
                <div class="text-gray-300 text-sm">
                    <span class="font-medium text-white">{{ isset($postsCount) ? $postsCount : $trendingPosts->count() }}</span> posts found
                </div>
            </div>
        </div>
        
        @if($trendingPosts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($trendingPosts as $post)
                    <div class="bg-gradient-to-br from-gray-900/70 to-gray-800/50 backdrop-blur-xl shadow-blue-900/20 rounded-xl overflow-hidden border border-gray-700/70 shadow-lg transform transition-all duration-300  hover:-translate-y-1 group post-card">
                        <div class="p-3 flex items-center justify-between border-b border-gray-800/50">
                            <a href="{{ route('profile.show', $post->user->username) }}" class="flex items-center gap-2 group/user">
                                <div class="relative">
                                    <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ $post->user->name }}" class="w-9 h-9 rounded-full object-cover border-2 border-transparent group-hover/user:border-blue-500 transition-all">
                                    <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-green-500 rounded-full border-2 border-gray-900 opacity-0 group-hover/user:opacity-100 transition-opacity"></div>
                                </div>
                                <div>
                                    <div class="flex items-center flex-wrap gap-1">
                                        <span class="font-semibold text-white group-hover/user:text-blue-400 transition-colors text-sm">{{ $post->user->name }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <a href="{{ route('posts.show', $post) }}" class="block relative overflow-hidden">
                            @if($post->images->count() > 0)
                                <div class="aspect-square overflow-hidden">
                                    <img src="{{ asset('storage/' . $post->images->first()->image_path) }}" alt="Post image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>
                            @elseif($post->image_path)
                                <div class="aspect-square overflow-hidden">
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>
                            @else
                                <div class="p-4 text-white bg-gradient-to-br from-gray-800/50 to-gray-900/50 aspect-square flex items-center">
                                    <p class="line-clamp-6 text-sm">{{ $post->caption }}</p>
                                </div>
                            @endif
                            
                            <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-between">
                                <div class="text-white text-sm font-medium line-clamp-1">
                                    {{ Str::limit($post->caption, 50) }}
                                </div>
                                <div class="bg-blue-500 rounded-full p-1.5 shadow-lg transform group-hover:rotate-45 transition-transform duration-300">
                                    <i class="ri-arrow-right-line text-white"></i>
                                </div>
                            </div>
                        </a>
                        
                        <div class="p-3 border-t border-gray-800/50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1 text-red-500">
                                        <i class="ri-heart-fill text-sm"></i>
                                        <span class="text-xs font-medium">{{ $post->likes_count }}</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-1 text-blue-500">
                                        <i class="ri-message-3-line text-sm"></i>
                                        <span class="text-xs font-medium">{{ $post->comments_count }}</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-1 text-green-500">
                                        <i class="ri-repeat-line text-sm"></i>
                                        <span class="text-xs font-medium">{{ $post->shares_count }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gradient-to-br from-gray-900/70 to-gray-800/50 backdrop-blur-xl rounded-xl overflow-hidden border border-gray-800/50 shadow-lg mb-6 p-8 text-center">
                <div class="bg-gray-800/50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ri-search-line text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">No posts found</h3>
                <p class="text-gray-400 mb-4 max-w-md mx-auto">No posts match your search criteria. Try changing your filters or come back later.</p>
                <a href="{{ route('trending.index') }}" class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-full transition-all transform hover:-translate-y-0.5 shadow-md">
                    <i class="ri-refresh-line"></i>
                    Reset filters
                </a>
            </div>
        @endif
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth < 768) {
        document.querySelectorAll('.post-card').forEach(card => {
            card.style.animationDuration = '0.3s';
        });
    }
    
    const cards = document.querySelectorAll('.post-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
            this.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.2)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });
});
</script>
@endsection