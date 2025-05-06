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
