<div class="bg-gray-900/50 rounded-xl overflow-hidden border border-gray-800 shadow-lg mb-6 backdrop-blur-sm transform transition-all duration-300 hover:shadow-blue-900/20 hover:border-gray-700 hover:-translate-y-1">
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
                    <i class="ri-more-2-fill"></i>
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
                <div class="flex space-x-4 overflow-x-auto pb-2 scrollbar-hide">
                    @foreach($post->images as $image)
                        <div class="flex-none w-[48%] sm:w-[50%] rounded-xl overflow-hidden border border-gray-700/70">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Post image" class="w-full h-auto object-cover aspect-square">
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif($post->images->count() == 1)
            <div class="px-4 pt-1 pb-2">
                <div class="w-full rounded-xl overflow-hidden border border-gray-700/70">
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
                    <button type="submit" class="flex items-center gap-1.5 text-red-500 hover:text-red-400 transition-colors group like-btn" data-post-id="{{ $post->id }}">
                        <i class="ri-heart-fill text-xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm font-medium like-count">{{ $post->likes->count() }}</span>
                    </button>
                </form>
            @else
                <form action="{{ route('likes.store', $post) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 text-gray-400 hover:text-red-500 transition-colors group like-btn" data-post-id="{{ $post->id }}">
                        <i class="ri-heart-line text-xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm font-medium like-count">{{ $post->likes->count() }}</span>
                    </button>
                </form>
            @endif
                
            <a href="{{ route('posts.show', $post) }}" class="flex items-center gap-1.5 text-gray-400 hover:text-blue-500 transition-colors group">
                <i class="ri-chat-3-line text-xl group-hover:scale-110 transition-transform"></i>
                <span class="text-sm font-medium comment-count-{{ $post->id }}">{{ $post->comments->count() }}</span>
            </a>
                
            <form action="{{ route('posts.share', $post) }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-1.5 text-gray-400 hover:text-green-500 transition-colors group {{ $post->isSharedBy(auth()->user()) ? 'text-green-500' : '' }} share-btn" data-post-id="{{ $post->id }}">
                    <i class="ri-repeat-line text-xl {{ $post->isSharedBy(auth()->user()) ? 'fill-current' : '' }}"></i>
                    <span class="text-sm font-medium share-count">{{ $post->shares->count() }}</span>
                </button>
            </form>
                
            @if($post->isBookmarkedBy(auth()->user()))
                <form action="{{ route('bookmarks.destroy', $post) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-1.5 text-blue-500 hover:text-blue-400 transition-colors group bookmark-btn" data-post-id="{{ $post->id }}">
                        <i class="ri-bookmark-fill text-xl group-hover:scale-110 transition-transform"></i>
                    </button>
                </form>
            @else
                <form action="{{ route('bookmarks.store', $post) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 text-gray-400 hover:text-blue-500 transition-colors group bookmark-btn" data-post-id="{{ $post->id }}">
                        <i class="ri-bookmark-line text-xl group-hover:scale-110 transition-transform"></i>
                    </button>
                </form>
            @endif
            </div>
        </div>
    </div>
</div>
