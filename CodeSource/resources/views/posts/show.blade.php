@extends('layouts.app')
@section('title', 'Show post')
@section('content')
<div class="container">
    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('profile.show', $post->user->username) }}" class="flex items-center gap-3">
                    <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ $post->user->name }}" class="avatar">
                    <div>
                        <div class="font-semibold">{{ $post->user->name }}</div>
                        <div class="text-xs text-gray-400">{{ '@' . $post->user->username }}</div>
                    </div>
                </a>
                
                @if($post->user_id === auth()->id())
                <div class="relative post-menu">
                    <button class="text-gray-400 hover:text-white p-1 rounded-full hover:bg-gray-800/50 transition-colors post-menu-toggle">
                        <i class="ri-more-2-fill text-xl"></i>
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
            
            <div class="card-body p-0">    
                @if($post->caption)
                    <div class="p-4">{{ $post->caption }}</div>
                @endif
                
                @if($post->images->count() > 0)
                    <div class="post-image-container">
                        @foreach($post->images as $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Post image" class="post-image">
                        @endforeach
                    </div>
                    
                    @if($post->images->count() > 1)
                        <div class="flex justify-center gap-1 p-2">
                            @foreach($post->images as $index => $image)
                                <div class="w-2 h-2 rounded-full {{ $index === 0 ? 'bg-blue-500' : 'bg-gray-500' }}"></div>
                            @endforeach
                        </div>
                    @endif
                @elseif($post->image_path)
                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post image" class="post-image">
                @endif
            </div>
            
            <div class="card-footer">
                <div class="post-actions">
                    @if($post->isLikedBy(auth()->user()))
                        <form action="{{ route('likes.destroy', $post) }}" method="POST" class="like-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="post-action text-red-500 like-btn" data-post-id="{{ $post->id }}">
                                <i class="ri-heart-fill text-2xl"></i>
                                <span class="like-count">{{ $post->likes->count() }}</span>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('likes.store', $post) }}" method="POST" class="like-form">
                            @csrf
                            <button type="submit" class="post-action text-gray-400 like-btn" data-post-id="{{ $post->id }}">
                                <i class="ri-heart-line text-2xl"></i>
                                <span class="like-count">{{ $post->likes->count() }}</span>
                            </button>
                        </form>
                    @endif
                    
                    <button type="button" class="post-action text-gray-400">
                        <i class="ri-chat-3-line text-2xl"></i>
                        <span class="comment-count-{{ $post->id }}">{{ $post->comments->count() }}</span>
                    </button>
                    
                    <form action="{{ route('posts.share', $post) }}" method="POST" class="share-form">
                        @csrf
                        <button type="submit" class="post-action {{ $post->isSharedBy(auth()->user()) ? 'text-green-500' : 'text-gray-400' }} share-btn" data-post-id="{{ $post->id }}">
                            <i class="ri-repeat-line text-2xl"></i>
                            <span class="share-count">{{ $post->shares->count() }}</span>
                        </button>
                    </form>
                    
                    @if($post->isBookmarkedBy(auth()->user()))
                        <form action="{{ route('bookmarks.destroy', $post) }}" method="POST" class="bookmark-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="post-action text-blue-500 bookmark-btn" data-post-id="{{ $post->id }}">
                                <i class="ri-bookmark-fill text-2xl"></i>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('bookmarks.store', $post) }}" method="POST" class="bookmark-form">
                            @csrf
                            <button type="submit" class="post-action text-gray-400 bookmark-btn" data-post-id="{{ $post->id }}">
                                <i class="ri-bookmark-line text-2xl"></i>
                            </button>
                        </form>
                    @endif
                </div>
                
                <div class="post-comments mt-12">
                    @foreach($post->comments as $comment)
                        <div class="comment" id="comment-{{ $comment->id }}">
                            <img src="{{ $comment->user->profile_image ? asset('storage/' . $comment->user->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ $comment->user->name }}" class="avatar w-8 h-8">
                            <div class="comment-content">
                                <div class="comment-user">{{ $comment->user->name }}</div>
                                <div class="comment-text">{{ $comment->content }}</div>
                                <div class="text-xs text-gray-400 mt-1">{{ $comment->created_at->diffForHumans() }}</div>
                            </div>
                            
                            @if($comment->user_id === auth()->id())
                                <div class="ml-auto relative comment-menu">
                                    <button type="button" class="text-gray-400 hover:text-white p-1 rounded-full hover:bg-gray-800/50 transition-colors comment-menu-toggle">
                                        <i class="ri-more-2-fill text-lg"></i>
                                    </button>
                                    <div class="absolute right-0 mt-2 w-32 bg-gray-800 rounded-md shadow-lg z-10 border border-gray-700 overflow-hidden comment-menu-dropdown hidden">
                                        <button type="button" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700 edit-comment-btn" data-comment-id="{{ $comment->id }}" data-comment-content="{{ $comment->content }}">Edit</button>
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-700">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    
                    <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-4 comment-form">
                        @csrf
                        <div class="flex gap-3">
                            <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ auth()->user()->name }}" class="avatar w-8 h-8">
                            <div class="flex-1">
                                <textarea name="content" class="form-control" placeholder="Add a comment..." rows="1" required></textarea>
                                <div class="flex justify-end mt-2">
                                    <button type="submit" class="btn btn-primary">Comment</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal pour éditer un commentaire -->
                <div id="edit-comment-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-gray-900 rounded-lg shadow-lg w-full max-w-md mx-4">
                        <div class="p-4 border-b border-gray-800">
                            <h3 class="text-lg font-semibold text-white">Edit Comment</h3>
                        </div>
                        <form id="edit-comment-form" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="p-4">
                                <textarea id="edit-comment-content" name="content" class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all resize-none" rows="4" required></textarea>
                            </div>
                            <div class="p-4 border-t border-gray-800 flex justify-end gap-3">
                                <button type="button" id="close-edit-modal" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les menus déroulants et autres fonctionnalités
    const commentToggleButtons = document.querySelectorAll('.comment-menu-toggle');
    commentToggleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const dropdown = this.closest('.comment-menu').querySelector('.comment-menu-dropdown');
            
            document.querySelectorAll('.comment-menu-dropdown').forEach(menu => {
                if (menu !== dropdown) menu.classList.add('hidden');
            });
            
            dropdown.classList.toggle('hidden');
        });
    });
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.comment-menu')) {
            document.querySelectorAll('.comment-menu-dropdown').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });
    
    const postToggleButtons = document.querySelectorAll('.post-menu-toggle');
    postToggleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const dropdown = this.closest('.post-menu').querySelector('.post-menu-dropdown');
            
            document.querySelectorAll('.post-menu-dropdown').forEach(menu => {
                if (menu !== dropdown) menu.classList.add('hidden');
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
    
    const editCommentModal = document.getElementById('edit-comment-modal');
    const editCommentForm = document.getElementById('edit-comment-form');
    const editCommentContent = document.getElementById('edit-comment-content');
    const closeEditModal = document.getElementById('close-edit-modal');
    const editCommentBtns = document.querySelectorAll('.edit-comment-btn');
    
    editCommentBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const commentContent = this.getAttribute('data-comment-content');
            
            this.closest('.comment-menu-dropdown').classList.add('hidden');
            
            editCommentForm.action = `/comments/${commentId}`;
            editCommentContent.value = commentContent;
            
            editCommentModal.classList.remove('hidden');
        });
    });
    
    closeEditModal.addEventListener('click', function() {
        editCommentModal.classList.add('hidden');
    });
    
    editCommentModal.addEventListener('click', function(e) {
        if (e.target === editCommentModal) {
            editCommentModal.classList.add('hidden');
        }
    });
});
</script>
@endsection
