@extends('layouts.app')

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
                <i data-feather="edit-2" class=""></i>
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
    
    <!-- Create Post Section (owner) -->
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
                        
                        <div id="preview-container" class="" style="display: none;">
                           
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="flex gap-3">
                                <label for="image-upload" class="cursor-pointer">
                                    <i data-feather="image" class="text-blue-500"></i>
                                </label>
                                <input type="file" id="image-upload" name="images[]" multiple accept="image/*" class="hidden">
                                
                                <label for="gif-upload" class="cursor-pointer">
                                    <i data-feather="film" class="text-blue-500"></i>
                                </label>
                                <input type="file" id="gif-upload" name="gif" accept="image/gif" class="hidden">
                                
                                <button type="button" class="cursor-pointer bg-transparent border-0 p-0">
                                    <i data-feather="list" class="text-blue-500"></i>
                                </button>
                                
                                <button type="button" class="cursor-pointer bg-transparent border-0 p-0">
                                    <i data-feather="smile" class="text-blue-500"></i>
                                </button>
                                
                                <button type="button" class="cursor-pointer bg-transparent border-0 p-0">
                                    <i data-feather="calendar" class="text-blue-500"></i>
                                </button>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" id="submitPost">Post</button>
                        </div>
                    </form>
                </div>
            </div>
    @endif
    
  

<div class="recent-publications-header">
    <h2 class="recent-publications-title">Recent Publications</h2>
    
    <div class="filter-container">
        <button class="filter-button">
            <i data-feather="filter" class="filter-icon"></i>
            <span>filter</span>
        </button>
    </div>
</div>

<!-- Publications Container -->
<div class="publications-container">
    @forelse($user->posts as $post)
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
            <i data-feather="more-horizontal"></i>
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
                            <i data-feather="chevron-left"></i>
                        </button>
                        <button class="pagination-arrow next-image" data-post-id="{{ $post->id }}">
                            <i data-feather="chevron-right"></i>
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
                            <i data-feather="heart" class="fill-current"></i>
                            <span>{{ $post->likes->count() }}</span>
                        </button>
                    </form>
                @else
                    <form action="{{ route('likes.store', $post) }}" method="POST">
                        @csrf
                        <button type="submit" class="publication-action">
                            <i data-feather="heart"></i>
                            <span>{{ $post->likes->count() }}</span>
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('posts.show', $post) }}" class="publication-action">
                    <i data-feather="message-circle"></i>
                    <span>{{ $post->comments->count() }}</span>
                </a>
                
                <a href="" class="publication-action">
                    <i data-feather="repeat"></i>
                    <span>{{ $post->shares->count() }}</span>
                </a>
                
                @if($post->isBookmarkedBy(auth()->user()))
                    <form action="{{ route('bookmarks.destroy', $post) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="publication-action active">
                            <i data-feather="bookmark" class="fill-current"></i>
                        </button>
                    </form>
                @else
                    <form action="{{ route('bookmarks.store', $post) }}" method="POST">
                        @csrf
                        <button type="submit" class="publication-action">
                            <i data-feather="bookmark"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="empty-publications">
            <div class="empty-icon">📷</div>
            <h3 class="empty-title">No posts yet</h3>
            <p class="empty-text">When {{ $user->id === auth()->id() ? 'you share' : $user->name . ' shares' }} posts, they'll appear here.</p>
        </div>
    @endforelse
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const imageUpload = document.getElementById('image-upload');
    if (imageUpload) {
        const imagePreview = document.getElementById('image-preview');
        const previewContainer = document.getElementById('preview-container');
        const form = document.getElementById('createPostForm');
        
        imageUpload.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                
                reader.readAsDataURL(this.files[0]);
            } else {
                previewContainer.style.display = 'none';
            }
        });
    }
    
   
    const menuButtons = document.querySelectorAll('.post-menu-btn');
    menuButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const menu = this.nextElementSibling;
            menu.classList.toggle('hidden');
        });
    });
    
   
    document.addEventListener('click', function() {
        document.querySelectorAll('.post-menu').forEach(menu => {
            if (!menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
   
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
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
});
</script>
@endsection