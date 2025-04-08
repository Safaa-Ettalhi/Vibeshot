@extends('layouts.app')

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
                    <div class="ml-auto relative">
                        <button class="btn btn-icon">
                            <i data-feather="more-horizontal"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-dark-card rounded-md shadow-lg z-10 hidden">
                            <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-800">Delete Post</button>
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
                        <form action="{{ route('likes.destroy', $post) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="post-action text-red-500">
                                <i data-feather="heart" class="fill-current"></i>
                                <span>{{ $post->likes->count() }}</span>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('likes.store', $post) }}" method="POST">
                            @csrf
                            <button type="submit" class="post-action">
                                <i data-feather="heart"></i>
                                <span>{{ $post->likes->count() }}</span>
                            </button>
                        </form>
                    @endif
                    
                    <button type="button" class="post-action">
                        <i data-feather="message-circle"></i>
                        <span>{{ $post->comments->count() }}</span>
                    </button>
                    
                    @if($post->isBookmarkedBy(auth()->user()))
                        <form action="{{ route('bookmarks.destroy', $post) }}" method="POST" class="ml-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="post-action text-blue-500">
                                <i data-feather="bookmark" class="fill-current"></i>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('bookmarks.store', $post) }}" method="POST" class="ml-auto">
                            @csrf
                            <button type="submit" class="post-action">
                                <i data-feather="bookmark"></i>
                            </button>
                        </form>
                    @endif
                </div>
                
                <div class="post-comments">
                    @foreach($post->comments as $comment)
                        <div class="comment">
                            <img src="{{ $comment->user->profile_image ? asset('storage/' . $comment->user->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ $comment->user->name }}" class="avatar w-8 h-8">
                            <div class="comment-content">
                                <div class="comment-user">{{ $comment->user->name }}</div>
                                <div>{{ $comment->content }}</div>
                                <div class="text-xs text-gray-400 mt-1">{{ $comment->created_at->diffForHumans() }}</div>
                            </div>
                            
                            @if($comment->user_id === auth()->id())
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500">Delete</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                    
                    <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-4">
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
            </div>
        </div>
    </div>
</div>
@endsection