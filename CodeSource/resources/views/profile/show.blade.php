@extends('layouts.app')
@section('title', 'show profile')
@section('content')
<div class="container">
    
    <div class="profile-card ">
    <div class="profile-header">

    <div class="profile-cover ">
    <img src="{{ $user->cover_image ? asset('storage/' . $user->cover_image) : asset('images/default-cover.png') }}" 
         alt="{{ $user->name }}"               
         class="cover-img ">
    </div>

   
    <div class="profile-pic-container ">
        <div class="relative">
            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.svg') }}" 
                 alt="{{ $user->name }}" 
                 class="profile-pic ">
        </div>
    </div>

   
    @if($user->id === auth()->id())
        <div class="edit-btn-container ">
            <a href="{{ route('profile.edit') }}" class="edit-profile-btn ">
                <i class="ri-edit-2-line"></i>
            </a>
        </div>
    @endif
</div>

        
        <!-- Profile Info -->
        <div class="profile-info ">
            <div class="profile-info-container ">
                <div class="user-details">
                <div class="flex items-center gap-6">
                    <h1 class="text-2xl font-bold">{{ $user->name }}  </h1>
                    <div class=" username"> {{'  @ ' . $user->username }}</div>
                </div>

                    <div class="follower-count ">{{ $followersCount }}+ followers</div>
                    
                    @if($user->bio)
                        <div class="user-bio ">{{ $user->bio }}</div>
                    @endif
                </div>
                
                @if($user->id !== auth()->id())
                    <div class="follow-actions">
                        @if($isFollowing)
                            <form action="{{ route('follow.destroy', $user) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="unfollow-btn ">Unfollow</button>
                            </form>
                        @else
                            <form action="{{ route('follow.store', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="follow-btn ">Follow</button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Create Post -->
    @if($user->id === auth()->id())
    <div class="card Create max-w-5xl">
        <div class="card-body">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="createPostForm">
                @csrf
                <div class="">
                    <div class="form-group flex space-x-3">
                        <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ auth()->user()->name }}" class="avatar mb-3">
                        <textarea name="caption" class="form-vibe" placeholder="Share your vibe!" rows="2"></textarea>
                    </div>
                </div>

                <div id="preview-container" class="grid grid-cols-3 gap-3 my-3" style="display: none;">
                    
                </div>
                
                <div class="flex justify-between items-center">
                    <div class="flex gap-3">
                        <label for="image-upload" class="cursor-pointer flex items-center gap-2 text-gray-300 hover:text-blue-400 transition-colors bg-gray-800/70 px-3 py-1.5 rounded-lg border border-gray-700/50 hover:border-blue-500/30">
                            <i class="ri-image-line text-lg"></i>
                            <span class="text-sm font-medium">Add images</span>
                        </label>
                        <input type="file" id="image-upload" name="images[]" multiple accept="image/*" class="hidden">
                    </div>
                    
                    <button type="submit" class="btn btn-primary flex items-center gap-2" id="submitPost">
                        Post
                        <i class="ri-send-plane-line"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
    
  

<div class="recent-publications-header">
    <h2 class="recent-publications-title">
        @if(isset($filter))
            @if($filter == 'recent')
                Recent Publications
            @elseif($filter == 'popular')
                Most Popular
            @elseif($filter == 'images')
                Images Only
            @elseif($filter == 'comments')
                Most Commented
            @elseif($filter == 'oldest')
                Oldest First
            @endif
        @else
            Recent Publications
        @endif
    </h2>
    
    <div class="filter-container relative">
        <button class="filter-button" id="filterButton">
            <i class="ri-filter-line filter-icon"></i>
            <span>Filter</span>
        </button>
        <div class="filter-dropdown hidden absolute right-0 mt-2 w-48 bg-gray-800 rounded-md shadow-lg z-10 border border-gray-700 overflow-hidden" id="filterDropdown">
            <a href="{{ route('profile.show', ['username' => $user->username, 'filter' => 'recent']) }}" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700 {{ $filter == 'recent' ? 'bg-blue-600' : '' }}">
                <i class="ri-time-line mr-2"></i> Recent
            </a>
            <a href="{{ route('profile.show', ['username' => $user->username, 'filter' => 'popular']) }}" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700 {{ $filter == 'popular' ? 'bg-blue-600' : '' }}">
                <i class="ri-heart-line mr-2"></i> Most Popular
            </a>
            <a href="{{ route('profile.show', ['username' => $user->username, 'filter' => 'comments']) }}" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700 {{ $filter == 'comments' ? 'bg-blue-600' : '' }}">
                <i class="ri-message-3-line mr-2"></i> Most Commented
            </a>
            <a href="{{ route('profile.show', ['username' => $user->username, 'filter' => 'images']) }}" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700 {{ $filter == 'images' ? 'bg-blue-600' : '' }}">
                <i class="ri-image-line mr-2"></i> Images Only
            </a>
            <a href="{{ route('profile.show', ['username' => $user->username, 'filter' => 'oldest']) }}" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700 {{ $filter == 'oldest' ? 'bg-blue-600' : '' }}">
                <i class="ri-history-line mr-2"></i> Oldest First
            </a>
        </div>
    </div>
</div>

<!-- Publications Container -->
<div class="publications-container">
    @forelse(isset($posts) ? $posts : $user->posts as $post)
        <div class="publication-card">
           
            <div class="publication-header">
                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.svg') }}" 
                     alt="{{ $user->name }}" 
                     class="publication-avatar">
                <div class="publication-user-info">
                    <div class="publication-user-name">{{ $user->name }}</div>
                    <div class="publication-user-handle">{{'@  ' . $user->username }} · <span class="publication-time">{{ $post->created_at->diffForHumans() }}</span></div>
                </div>
                
                @if($user->id === auth()->id())
    <div class="publication-menu">
        <button class="publication-menu-btn">
            <i class="ri-more-2-fill"></i>
        </button>
        <div class="publication-menu-dropdown hidden">
            <form action="{{ route('posts.destroy', $post) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="publication-menu-item">Delete Post</button>
            </form>
            @if(!$post->original_post_id)
                <a href="{{ route('posts.edit', $post) }}" class="publication-menu-item edit-post">Update Post</a>
            @endif
        </div>
    </div>
@endif
            </div>
            
            @if($post->caption)
                <div class="publication-caption">{{ $post->caption }}</div>
            @endif
            
           
            <div class="publication-images-container">
                <div class="publication-images-scroll" data-current-image="0" data-total-images="{{ $post->images->count() }}">
                    @foreach($post->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                             alt="Post image" 
                             class="publication-image"
                             loading="lazy">
                    @endforeach
                </div>
                
                
                @if($post->images->count() > 1)
                    <div class="image-pagination-controls">
                        <button class="pagination-arrow prev-image" data-post-id="{{ $post->id }}">
                            <i class="ri-arrow-left-s-line"></i>
                        </button>
                        <button class="pagination-arrow next-image" data-post-id="{{ $post->id }}">
                            <i class="ri-arrow-right-s-line"></i>
                        </button>
                    </div>
                    
                    <div class="pagination-indicators">
                        @foreach($post->images as $index => $image)
                            <div class="pagination-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Post Actions -->
            <div class="publication-actions">
                @if($post->isLikedBy(auth()->user()))
                    <form action="{{ route('likes.destroy', $post) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="publication-action liked">
                            <i class="ri-heart-fill"></i>
                            <span>{{ $post->likes->count() }}</span>
                        </button>
                    </form>
                @else
                    <form action="{{ route('likes.store', $post) }}" method="POST">
                        @csrf
                        <button type="submit" class="publication-action">
                            <i class="ri-heart-line"></i>
                            <span>{{ $post->likes->count() }}</span>
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('posts.show', $post) }}" class="publication-action">
                    <i class="ri-message-3-line"></i>
                    <span>{{ $post->comments->count() }}</span>
                </a>
                
                <a href="" class="publication-action">
                    <i class="ri-repeat-line"></i>
                    <span>{{ $post->shares->count() }}</span>
                </a>
                
                @if($post->isBookmarkedBy(auth()->user()))
                    <form action="{{ route('bookmarks.destroy', $post) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="publication-action active">
                            <i class="ri-bookmark-fill"></i>
                        </button>
                    </form>
                @else
                    <form action="{{ route('bookmarks.store', $post) }}" method="POST">
                        @csrf
                        <button type="submit" class="publication-action">
                            <i class="ri-bookmark-line"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="empty-publications">
        <div class="empty-icon bg-gray-800/50 w-20 h-20 rounded-full flex items-center justify-center"> <i class="ri-camera-line text-4xl text-blue-500"></i></div>
            <h3 class="empty-title">No posts yet</h3>
            <p class="empty-text">When {{ $user->id === auth()->id() ? 'you share' : $user->name . ' shares' }} posts, they'll appear here.</p>
        </div>
    @endforelse
</div>
</div>

<style>
.filter-dropdown {
    transition: all 0.2s ease;
    transform-origin: top right;
}

.filter-dropdown.hidden {
    display: none;
    opacity: 0;
    transform: scale(0.95);
}

.filter-dropdown:not(.hidden) {
    display: block;
    opacity: 1;
    transform: scale(1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButton = document.getElementById('filterButton');
    const filterDropdown = document.getElementById('filterDropdown');
    
    if (filterButton && filterDropdown) {
        filterButton.addEventListener('click', function(e) {
            e.stopPropagation();
            filterDropdown.classList.toggle('hidden');
        });
        
        document.addEventListener('click', function(e) {
            if (!filterButton.contains(e.target) && !filterDropdown.contains(e.target)) {
                filterDropdown.classList.add('hidden');
            }
        });
    }
  
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

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
                    previewContainer.style.display = 'none';
                }
            }
        });

        function refreshPreviews() {

            previewContainer.innerHTML = '';

            if (selectedFiles.length > 0) {
                previewContainer.style.display = 'grid';
                selectedFiles.forEach((file, index) => {
                    createImagePreview(file, index);
                });
            } else {
                previewContainer.style.display = 'none';
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
                removeButton.innerHTML = '<i class="ri-close-line"></i>';
                
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

    const menuButtons = document.querySelectorAll('.publication-menu-btn');
    menuButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = this.parentNode.querySelector('.publication-menu-dropdown');
            dropdown.classList.toggle('hidden');
        });
    });

    document.addEventListener('click', function() {
        document.querySelectorAll('.publication-menu-dropdown').forEach(menu => {
            if (!menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
            }
        });
    });
    function navigateImages(container, direction) {
        const imagesScroll = container.querySelector('.publication-images-scroll');
        const totalImages = parseInt(imagesScroll.getAttribute('data-total-images'));
        let currentIndex = parseInt(imagesScroll.getAttribute('data-current-image'));
        
        if (direction === 'next') {
            currentIndex = (currentIndex + 1) % totalImages;
        } else {
            currentIndex = (currentIndex - 1 + totalImages) % totalImages;
        }
        
        imagesScroll.setAttribute('data-current-image', currentIndex);
        imagesScroll.style.transform = `translateX(-${currentIndex * 100}%)`;
        const indicators = container.querySelectorAll('.pagination-dot');
        indicators.forEach((dot, idx) => {
            if (idx === currentIndex) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }
    const prevButtons = document.querySelectorAll('.prev-image');
    const nextButtons = document.querySelectorAll('.next-image');
    
    prevButtons.forEach(button => {
        button.addEventListener('click', function() {
            const container = this.closest('.publication-images-container');
            navigateImages(container, 'prev');
        });
    });
    
    nextButtons.forEach(button => {
        button.addEventListener('click', function() {
            const container = this.closest('.publication-images-container');
            navigateImages(container, 'next');
        });
    });
    document.querySelectorAll('.pagination-dot').forEach(dot => {
        dot.addEventListener('click', function() {
            const container = this.closest('.publication-images-container');
            const imagesScroll = container.querySelector('.publication-images-scroll');
            const index = parseInt(this.getAttribute('data-index'));
            
            imagesScroll.setAttribute('data-current-image', index);
            imagesScroll.style.transform = `translateX(-${index * 100}%)`;
            const indicators = container.querySelectorAll('.pagination-dot');
            indicators.forEach((d, i) => {
                if (i === index) {
                    d.classList.add('active');
                } else {
                    d.classList.remove('active');
                }
            });
        });
    });
});
</script>
@endsection