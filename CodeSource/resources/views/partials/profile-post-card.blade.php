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
                <button type="submit" class="publication-action liked like-btn" data-post-id="{{ $post->id }}">
                    <i class="ri-heart-fill"></i>
                    <span class="like-count">{{ $post->likes->count() }}</span>
                </button>
            </form>
        @else
            <form action="{{ route('likes.store', $post) }}" method="POST">
                @csrf
                <button type="submit" class="publication-action like-btn" data-post-id="{{ $post->id }}">
                    <i class="ri-heart-line"></i>
                    <span class="like-count">{{ $post->likes->count() }}</span>
                </button>
            </form>
        @endif
        
        <a href="{{ route('posts.show', $post) }}" class="publication-action">
            <i class="ri-message-3-line"></i>
            <span class="comment-count-{{ $post->id }}">{{ $post->comments->count() }}</span>
        </a>
        
        <form action="{{ route('posts.share', $post) }}" method="POST">
            @csrf
            <button type="submit" class="publication-action {{ $post->isSharedBy(auth()->user()) ? 'active ' : '' }} share-btn" data-post-id="{{ $post->id }}">
                <i class="ri-repeat-line"></i>
                <span class="share-count">{{ $post->shares->count() }}</span>
            </button>
        </form>
        
        @if($post->isBookmarkedBy(auth()->user()))
            <form action="{{ route('bookmarks.destroy', $post) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="publication-action active bookmark-btn" data-post-id="{{ $post->id }}">
                    <i class="ri-bookmark-fill"></i>
                </button>
            </form>
        @else
            <form action="{{ route('bookmarks.store', $post) }}" method="POST">
                @csrf
                <button type="submit" class="publication-action bookmark-btn" data-post-id="{{ $post->id }}">
                    <i class="ri-bookmark-line"></i>
                </button>
            </form>
        @endif
    </div>
</div>
