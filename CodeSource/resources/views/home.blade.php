@extends('layouts.app')

@section('content')
<div class="container bg-black min-h-screen">
    <div class="flex flex-col md:flex-row gap-6 py-4">
        <div class="w-full md:w-2/3">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-white">Home</h1>
            </div>
            
            <!-- Create Post -->
            <div class="bg-gray-900/50 rounded-xl overflow-hidden border border-gray-800 shadow-lg mb-6 backdrop-blur-sm">
                <div class="p-4">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="createPostForm">
                        @csrf
                        <div class="flex items-start gap-3 mb-3 w-full">
                            <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-blue-500/30">
                            <div class="w-full">
                                <textarea name="caption" class="w-full bg-gray-800/50 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all resize-none" placeholder="Share your vibe!" rows="2"></textarea>
                            </div>
                        </div>
                        
                        <div id="preview-container" class="mb-3 grid grid-cols-2 md:grid-cols-3 gap-2 hidden">

                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="flex gap-4">
                                <label for="image-upload" class="cursor-pointer text-blue-500 hover:text-blue-400 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                </label>
                                <input type="file" id="image-upload" name="images[]" multiple accept="image/*" class="hidden">
                                
                                <label for="gif-upload" class="cursor-pointer text-blue-500 hover:text-blue-400 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-film"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect><line x1="7" y1="2" x2="7" y2="22"></line><line x1="17" y1="2" x2="17" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line><line x1="2" y1="7" x2="7" y2="7"></line><line x1="2" y1="17" x2="7" y2="17"></line><line x1="17" y1="17" x2="22" y2="17"></line><line x1="17" y1="7" x2="22" y2="7"></line></svg>
                                </label>
                                <input type="file" id="gif-upload" name="gif" accept="image/gif" class="hidden">
                                
                                <button type="button" class="text-blue-500 hover:text-blue-400 transition-colors bg-transparent border-0 p-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                </button>
                                
                                <button type="button" class="text-blue-500 hover:text-blue-400 transition-colors bg-transparent border-0 p-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-smile"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                                </button>
                                
                                <button type="button" class="text-blue-500 hover:text-blue-400 transition-colors bg-transparent border-0 p-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                </button>
                            </div>
                            
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-full transition-all transform hover:-translate-y-0.5 shadow-md" id="submitPost">Post</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Posts Feed -->
            @foreach($posts as $post)
                <div class="bg-gray-900/50 rounded-xl overflow-hidden border border-gray-800 shadow-lg mb-6 backdrop-blur-sm transform transition-all duration-300 hover:shadow-blue-900/20 hover:border-gray-700">
                    <div class="p-4 flex items-center justify-between border-b border-gray-800/50">
                        <a href="{{ route('profile.show', $post->user->username) }}" class="flex items-center gap-3 group">
                            <div class="relative">
                                <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-transparent group-hover:border-blue-500 transition-all">
                               
                            </div>
                            <div>
                                <div class="flex items-center flex-wrap gap-1">
                                    <span class="font-semibold text-white group-hover:text-blue-400 transition-colors">{{ $post->user->name }}</span>
                                    <span class="text-gray-400">{{ '@' . $post->user->username }}</span>
                                    <span class="text-gray-500 px-1">·</span>
                                    <span class="text-gray-400 text-sm">{{ App\Helpers\TimeHelper::shortDiffForHumans($post->created_at) }}</span>
                                </div>
                            </div>
                        </a>
                        
                        @if($post->user_id === auth()->id())
    <div class="relative post-menu">
        <button class="text-gray-400 hover:text-white p-1 rounded-full hover:bg-gray-800/50 transition-colors post-menu-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
        </button>
        <div class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-md shadow-lg z-10 border border-gray-700 overflow-hidden post-menu-dropdown hidden">
            @if(!$post->original_post_id)
                <a href="{{ route('posts.edit', $post) }}" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700">Edit Post</a>
            @endif
            <form action="{{ route('posts.destroy', $post) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-700">Delete Post</button>
            </form>
        </div>
    </div>
@endif
                    </div>
                    
                    <div class="p-0">
                        @if($post->caption)
                            <div class="p-4 text-white">{{ $post->caption }}</div>
                        @endif
                        
                        @if($post->images->count() > 1)
                            <div class="px-4 pt-1 pb-2">
                                <!-- container image lune acoter de lautre -->
                                <div class="flex space-x-4 overflow-x-auto pb-2 scrollbar-hide">
                                    @foreach($post->images as $image)
                                        <div class="flex-none w-[48%] sm:w-[50%] rounded-xl overflow-hidden">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Post image" class="w-full h-auto object-cover aspect-square">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($post->images->count() == 1)
                            <div class="px-4 pt-1 pb-2">
                                <div class="w-full rounded-xl overflow-hidden">
                                    <img src="{{ asset('storage/' . $post->images->first()->image_path) }}" alt="Post image" class="w-full h-auto object-cover">
                                </div>
                            </div>
                        @elseif($post->image_path)
                            <div class="px-4 pt-1 pb-2">
                                <div class="w-full rounded-xl overflow-hidden">
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post image" class="w-full h-auto object-cover">
                                </div>
                            </div>
                        @endif
                    </div>
                    

<div class="p-4 border-t border-gray-800/50">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-6">
            @if($post->isLikedBy(auth()->user()))
                <form action="{{ route('likes.destroy', $post) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-1.5 text-red-500 hover:text-red-400 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart group-hover:scale-110 transition-transform"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        <span class="text-sm font-medium">{{ $post->likes->count() }}</span>
                    </button>
                </form>
            @else
                <form action="{{ route('likes.store', $post) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 text-gray-400 hover:text-red-500 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart group-hover:scale-110 transition-transform"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        <span class="text-sm font-medium">{{ $post->likes->count() }}</span>
                    </button>
                </form>
            @endif
            
            <a href="{{ route('posts.show', $post) }}" class="flex items-center gap-1.5 text-gray-400 hover:text-blue-500 transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle group-hover:scale-110 transition-transform"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                <span class="text-sm font-medium">{{ $post->comments->count() }}</span>
            </a>
            
            <form action="{{ route('posts.share', $post) }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-1.5 text-gray-400 hover:text-green-500 transition-colors group {{ $post->isSharedBy(auth()->user()) ? 'text-green-500' : '' }}">
                    <i data-feather="repeat" width="18" height="18" class="{{ $post->isSharedBy(auth()->user()) ? 'fill-current' : '' }}"></i>
                    <span class="text-sm font-medium">{{ $post->shares->count() }}</span>
                </button>
            </form>
            
            @if($post->isBookmarkedBy(auth()->user()))
                <form action="{{ route('bookmarks.destroy', $post) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-1.5 text-blue-500 hover:text-blue-400 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark group-hover:scale-110 transition-transform"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
                    </button>
                </form>
            @else
                <form action="{{ route('bookmarks.store', $post) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 text-gray-400 hover:text-blue-500 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark group-hover:scale-110 transition-transform"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
                </div>
            @endforeach
               
        </div>
        
        <div class="w-full md:w-1/3">
            
            <div class="relative mb-6">
                <input type="text" placeholder="Search" class="w-full bg-gray-800/70 border border-gray-700 rounded-full py-2 px-4 pl-10 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            

            <div class="bg-gray-900/50 rounded-xl overflow-hidden border border-gray-800 shadow-lg mb-6 backdrop-blur-sm">
                <div class="p-4 border-b border-gray-800/50 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up text-red-500 mr-2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                    <h3 class="font-semibold text-white">Today's Trending</h3>
                </div>
                <div class="p-2">
                @foreach($trendingPosts as $trending)
    <a href="{{ route('posts.show', $trending) }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-800/50 mb-2 transition-colors">
        <img src="{{ 
            $trending->images->count() > 0 
                ? asset('storage/' . $trending->images->random()->image_path) 
                : ($trending->image_path 
                    ? asset('storage/' . $trending->image_path) 
                    : asset('images/default-post-image.svg')) 
        }}" alt="Trending post" class="w-12 h-12 rounded-lg object-cover shadow-sm">
        <div class="flex-1 min-w-0">
            <div class="font-semibold text-white truncate">{{ Str::limit($trending->caption, 30) }}</div>
            <div class="text-xs text-gray-400 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                {{ $trending->likes_count }} likes
            </div>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right text-gray-500"><polyline points="9 18 15 12 9 6"></polyline></svg>
    </a>
@endforeach
                    
                    <a href="#" class="text-sm text-blue-500 hover:text-blue-400 transition-colors block text-center py-2 rounded-lg hover:bg-gray-800/30">See All</a>
                </div>
            </div>
            
            <!-- People You Might Know -->
            <div class="bg-gray-900/50 rounded-xl overflow-hidden border border-gray-800 shadow-lg backdrop-blur-sm">
                <div class="p-4 border-b border-gray-800/50">
                    <h3 class="font-semibold text-white">People you might know</h3>
                </div>
                <div class="p-2">
                    @foreach($suggestedUsers as $user)
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-800/50 mb-2 transition-colors">
                            <a href="{{ route('profile.show', $user->username) }}" class="flex items-center gap-3">
                                <div class="relative">
                                    <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-transparent hover:border-blue-500 transition-all">
                                  
                                </div>
                                <div>
                                    <div class="font-semibold text-white">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ '@' . $user->username }}</div>
                                </div>
                            </a>
                            
                            <form action="{{ route('follow.store', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white text-xs font-medium py-1 px-3 rounded-full transition-colors">Follow</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="text-xs text-gray-500 mt-6 text-center">
                Copyright © 2025<br>
                All rights reserved to the dev team
            </div>
        </div>
    </div>
</div>

<style>

.scrollbar-hide {

  scrollbar-width: none;

  -ms-overflow-style: none;
}

.scrollbar-hide::-webkit-scrollbar {
  display: none;
  width: 0;
  height: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const toggleButtons = document.querySelectorAll('.post-menu-toggle');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = this.nextElementSibling;

            document.querySelectorAll('.post-menu-dropdown').forEach(menu => {
                if (menu !== dropdown) {
                    menu.classList.add('hidden');
                }
            });

            dropdown.classList.toggle('hidden');
        });
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.post-menu')) {
            document.querySelectorAll('.post-menu-dropdown').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });

    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    const imageUpload = document.getElementById('image-upload');
    const previewContainer = document.getElementById('preview-container');
    
    if (imageUpload) {
        imageUpload.addEventListener('change', function() {
            previewContainer.innerHTML = '';
            previewContainer.classList.remove('hidden');
            
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'relative';
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-32 object-cover rounded-lg';
                        
                        previewDiv.appendChild(img);
                        previewContainer.appendChild(previewDiv);
                    }
                    
                    reader.readAsDataURL(file);
                }
            }
        });
    }
});
</script>
@endsection