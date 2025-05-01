@extends('layouts.app')

@section('title', 'Home')
@section('content')
<div class="container bg-black min-h-screen">
    <div class="flex flex-col md:flex-row gap-6 py-4">
        <div class="w-full md:w-2/3">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-white">Home</h1>
                @if(request()->has('search') && request('search'))
                    <div class="bg-gray-800/50 text-gray-300 text-sm py-1 px-3 rounded-full">
                        {{ $resultsCount }} result{{ $resultsCount > 1 ? 's' : '' }} found
                    </div>
                @endif
            </div>
            
            <!--create poste  -->
<div class="bg-gray-900/50 rounded-xl overflow-hidden border border-gray-800 shadow-lg mb-6 backdrop-blur-sm hover:border-gray-700 transition-all">
    <div class="p-5">
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="createPostForm">
            @csrf
            <div class="flex items-start gap-4 mb-4 w-full">
                <div class="relative">
                    <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-avatar.svg') }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-12 h-12 rounded-full object-cover border-2 border-blue-500/30 shadow-md">
                   
                </div>
                <div class="w-full">
                    <textarea name="caption" 
                              class="w-full bg-gray-800/70 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all resize-none shadow-inner" 
                              placeholder="Share your vibe..." 
                              rows="3"></textarea>
                </div>
            </div>
            
           
            <div id="preview-container" class="mb-4 grid grid-cols-2 md:grid-cols-3 gap-3 hidden">
              
            </div>
            
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <label for="image-upload" class="cursor-pointer flex items-center gap-2 text-gray-300 hover:text-blue-400 transition-colors bg-gray-800/70 px-3 py-1.5 rounded-lg border border-gray-700/50 hover:border-blue-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                        <span class="text-sm font-medium">Add images</span>
                    </label>
                    <input type="file" id="image-upload" name="images[]" multiple accept="image/*" class="hidden">
                </div>
                
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-5 rounded-full transition-all transform hover:-translate-y-0.5 shadow-md hover:shadow-blue-600/30 flex items-center gap-2" id="submitPost">
                    <span>Post</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </div>
        </form>
    </div>
</div>
            
            <!-- Posts Feed -->
            <div id="posts-container">
    @if(count($posts) > 0)
        @foreach($posts as $post)
            @include('partials.post-card', ['post' => $post])
        @endforeach
    @elseif(isset($searchPerformed) && $searchPerformed)
        <div class="bg-gray-900/50 rounded-xl overflow-hidden border border-gray-800 shadow-lg mb-6 backdrop-blur-sm p-8 text-center">
            <div class="bg-blue-900/30 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="ri-search-line text-4xl text-blue-500"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-3">No posts found</h3>
            <p class="text-gray-400 mb-4 max-w-md mx-auto">We couldn't find any results for "{{ $query }}". Try a different search term or explore other content.</p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-full transition-all transform hover:-translate-y-0.5 shadow-md">
                <i class="ri-home-line text-xl"></i>
                Back to home
            </a>
        </div>
    @endif
</div>
               
        </div>
        
        <div class="w-full md:w-1/3">
            
            <div class="relative mb-6 group">
                <form id="searchForm" action="{{ route('home') }}" method="GET" class="relative">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Search" value="{{ $query ?? '' }}" class="w-full bg-gray-800/70 border border-gray-700 rounded-full py-3 px-4 pl-12 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all shadow-md hover:shadow-blue-900/10" id="searchInput">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="ri-search-line text-xl"></i>
                        </div>
                    </div>
                    
                </form>
                @if(request()->has('search') && request('search'))
                    <div class="absolute -bottom-8 left-0 text-sm text-gray-400 flex items-center">
                        <span>Results for: <span class="text-blue-400 font-medium">{{ request('search') }}</span></span>
                        <a href="{{ route('home') }}" class="ml-2 text-gray-400 hover:text-blue-400 flex items-center gap-1 transition-colors">
                            <i class="ri-close-line text-lg"></i>
                            <span>Clear</span>
                        </a>
                    </div>
                @endif
            </div>
            

            <div class="bg-gray-900/50 rounded-xl overflow-hidden border border-gray-800 shadow-lg mb-6 backdrop-blur-sm">
                <div class="p-4 border-b border-gray-800/50 flex items-center justify-between">
                    <div class="flex items-center">
                    <i class="ri-fire-line text-orange-500 text-lg mr-2" style="font-size: 24px;"></i>
                        <h3 class="font-semibold text-white">Trending Posts</h3>
                    </div>
                   
                </div>
                <div class="p-2">
                @foreach($trendingPosts as $trending)
                    <a href="{{ route('posts.show', $trending) }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-800/50 mb-2 transition-colors">
                        <div class="relative w-12 h-12 rounded-lg overflow-hidden shadow-sm">
                            <img src="{{ 
                                $trending->images->count() > 0 
                                    ? asset('storage/' . $trending->images->random()->image_path) 
                                    : ($trending->image_path 
                                        ? asset('storage/' . $trending->image_path) 
                                        : asset('images/default-post-image.svg')) 
                            }}" alt="Trending post" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-white truncate">{{ Str::limit($trending->caption, 30) }}</div>
                            <div class="text-xs text-gray-400 flex items-center gap-1">
                                <i class="ri-heart-line text-sm"></i>
                                {{ $trending->likes_count }} likes
                            </div>
                        </div>
                        <i class="ri-arrow-right-s-line text-gray-500"></i>
                    </a>
                @endforeach
                    
                    <a href="{{ route('trending.index') }}" class="text-sm text-blue-500 hover:text-blue-400 transition-colors block text-center py-2 rounded-lg hover:bg-gray-800/30">See All </a>
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
    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium py-1 px-3 rounded-full transition-colors follow-btn" data-user-id="{{ $user->id }}">Follow</button>
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

.scrollbar-hide {
  scrollbar-width: none;
  -ms-overflow-style: none;
}

.scrollbar-hide::-webkit-scrollbar {
  display: none;
  width: 0;
  height: 0;
}

.new-shared-post {
  animation: fadeInDown 0.6s ease-out forwards;
  transform-origin: top center;
  opacity: 0;
}

@keyframes fadeInDown {
  0% {
    opacity: 0;
    transform: translateY(-20px) scale(0.98);
  }
  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
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

    const imageUpload = document.getElementById('image-upload');
    const previewContainer = document.getElementById('preview-container');
    
    if (imageUpload && previewContainer) {
       
        let selectedFiles = [];
        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            imageUpload.files = dataTransfer.files;
        }
        previewContainer.addEventListener('click', function(e) {
            
            if (e.target.closest('.image-remove-btn')) {
                const removeButton = e.target.closest('.image-remove-btn');
                const previewDiv = removeButton.closest('.image-preview-item');
                const imageIndex = parseInt(previewDiv.dataset.index);
                selectedFiles.splice(imageIndex, 1);
                updateFileInput();
                refreshPreviews();
                if (selectedFiles.length === 0) {
                    previewContainer.classList.add('hidden');
                }
            }
        });
        function refreshPreviews() {
            previewContainer.innerHTML = '';
            if (selectedFiles.length > 0) {
                previewContainer.classList.remove('hidden');
                selectedFiles.forEach((file, index) => {
                    createImagePreview(file, index);
                });
            } else {
                previewContainer.classList.add('hidden');
            }
        }
        function createImagePreview(file, index) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'relative rounded-lg overflow-hidden aspect-square image-preview-item';
                previewDiv.dataset.index = index;
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-full object-cover';
                
                const removeButton = document.createElement('button');
                removeButton.className = 'absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors image-remove-btn';
                removeButton.type = 'button'; // Important pour éviter la soumission du formulaire
                removeButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
                
                previewDiv.appendChild(img);
                previewDiv.appendChild(removeButton);
                previewContainer.appendChild(previewDiv);
            };
            
            reader.readAsDataURL(file);
        }

        imageUpload.addEventListener('change', function() {
            for (let i = 0; i < this.files.length; i++) {
                selectedFiles.push(this.files[i]);
            }
            refreshPreviews();
        });
    }
});
</script>
@endsection