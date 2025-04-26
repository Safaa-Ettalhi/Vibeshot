@extends('admin.layouts.app')

@section('title', 'Comments Management')
@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="text-xl sm:text-2xl font-bold text-white">Comments Management</h1>   
    <form action="{{ route('admin.comments.index') }}" method="GET" class="search-input flex items-center rounded-full border border-gray-600 px-3 sm:px-4 py-1.5 sm:py-2 w-full sm:w-auto">
        <i data-feather="search" class="text-gray-400 w-4 h-4 mr-2"></i>
        <input 
            type="text" 
            name="search" 
            placeholder="Search for a comment..." 
            value="{{ request('search') }}"
            class="bg-transparent focus:outline-none text-white placeholder-gray-400 w-full text-sm"
        >
    </form>
</div>

<!-- Mobile view -->
<div class="block md:hidden">
    <div class="space-y-4">
        @foreach($comments as $comment)
        <div class="bg-[#111111] rounded-xl border border-gray-800 shadow-lg overflow-hidden backdrop-blur-sm p-4">
            <div class="flex items-start gap-3 mb-3">
                <img 
                    src="{{ $comment->user->profile_image ? asset('storage/' . $comment->user->profile_image) : asset('images/default-avatar.svg') }}" 
                    alt="{{ $comment->user->name }}" 
                    class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-800"
                >
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start">
                        <div class="flex flex-col">
                            <span class="font-medium text-white">{{ $comment->user->name }}</span>
                            <span class="text-xs text-gray-400">{{ '@' . $comment->user->username }}</span>
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $comment->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="mt-2 text-gray-300 text-sm break-words">
                        {{ $comment->content }}
                    </div>
                </div>
            </div>
            
            <div class="mt-3 pt-3 border-t border-gray-800/50">
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.posts.show', $comment->post) }}" class="flex items-center gap-2 group">
                        <div class="h-8 w-8 rounded-lg overflow-hidden bg-[#1a1a1a] flex items-center justify-center">
                            @if($comment->post->image_path)
                                <img src="{{ asset('storage/' . $comment->post->image_path) }}" alt="Post" class="h-8 w-8 object-cover">
                            @elseif($comment->post->images->count() > 0)
                                <img src="{{ asset('storage/' . $comment->post->images->first()->image_path) }}" alt="Post" class="h-8 w-8 object-cover">
                            @else
                                <i data-feather="image" class="h-4 w-4 text-gray-600"></i>
                            @endif
                        </div>
                        <span class="text-xs text-gray-400 group-hover:text-blue-400 transition-colors truncate max-w-[140px]">
                            {{ Str::limit($comment->post->caption, 20) ?: 'No caption' }}
                        </span>
                    </a>
                    
                    <div class="flex items-center gap-2">
                        <a 
                            href="{{ route('admin.comments.show', $comment) }}" 
                            class="p-2 rounded-lg bg-[#1a1a1a] hover:bg-[#222222] text-gray-300 hover:text-white transition-colors duration-150"
                            title="View details"
                        >
                            <i data-feather="eye" class="h-4 w-4"></i>
                        </a>
                        
                        <form 
                            action="{{ route('admin.comments.destroy', $comment) }}" 
                            method="POST" 
                            class="inline" 
                            onsubmit="return confirm('Are you sure you want to delete this comment?');"
                        >
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="p-2 rounded-lg bg-red-900/20 hover:bg-red-900/50 text-red-400 hover:text-red-200 transition-colors duration-150"
                                title="Delete"
                            >
                                <i data-feather="trash-2" class="h-4 w-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Desktop view -->
<div class="hidden md:block bg-[#111111] rounded-xl border border-gray-800 shadow-lg overflow-hidden backdrop-blur-sm">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-800 bg-[#111111]/90">
                    <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Author</th>
                    <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Comment</th>
                    <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Post</th>
                    <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @foreach($comments as $comment)
                <tr class="hover:bg-[#1a1a1a] transition-colors duration-150">
                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 h-9 w-9 lg:h-10 lg:w-10">
                                <img 
                                    src="{{ $comment->user->profile_image ? asset('storage/' . $comment->user->profile_image) : asset('images/default-avatar.svg') }}" 
                                    alt="{{ $comment->user->name }}" 
                                    class="h-9 w-9 lg:h-10 lg:w-10 rounded-full object-cover ring-2 ring-gray-800"
                                >
                            </div>
                            <div class="flex flex-col">
                                <span class="font-medium text-white truncate max-w-[120px] lg:max-w-[180px]">{{ $comment->user->name }}</span>
                                <span class="text-xs text-gray-400">{{ '@' . $comment->user->username }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 lg:px-6 py-4">
                        <div class="max-w-[150px] md:max-w-[200px] lg:max-w-[250px] text-gray-300 text-sm truncate">{{ $comment->content }}</div>
                    </td>
                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 h-8 w-8 lg:h-10 lg:w-10 rounded-lg overflow-hidden">
                                @if($comment->post->image_path)
                                    <img src="{{ asset('storage/' . $comment->post->image_path) }}" alt="Post" class="h-8 w-8 lg:h-10 lg:w-10 object-cover">
                                @elseif($comment->post->images->count() > 0)
                                    <img src="{{ asset('storage/' . $comment->post->images->first()->image_path) }}" alt="Post" class="h-8 w-8 lg:h-10 lg:w-10 object-cover">
                                @else
                                    <div class="h-8 w-8 lg:h-10 lg:w-10 bg-[#1a1a1a] flex items-center justify-center">
                                        <i data-feather="image" class="h-4 w-4 lg:h-5 lg:w-5 text-gray-600"></i>
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('admin.posts.show', $comment->post) }}" class="text-sm text-gray-300 hover:text-blue-400 transition-colors duration-150 truncate max-w-[100px] lg:max-w-[150px]">
                                {{ Str::limit($comment->post->caption, 20) ?: 'No caption' }}
                            </a>
                        </div>
                    </td>
                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                        <div class="flex items-center">
                            <i data-feather="clock" class="h-4 w-4 mr-1.5 text-gray-500"></i>
                            {{ $comment->created_at->format('d/m/Y H:i') }}
                        </div>
                    </td>
                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center gap-2">
                            <a 
                                href="{{ route('admin.comments.show', $comment) }}" 
                                class="p-2 rounded-lg bg-[#1a1a1a] hover:bg-[#222222] text-gray-300 hover:text-white transition-colors duration-150"
                                title="View details"
                            >
                                <i data-feather="eye" class="h-4 w-4"></i>
                            </a>
                            
                            <form 
                                action="{{ route('admin.comments.destroy', $comment) }}" 
                                method="POST" 
                                class="inline" 
                                onsubmit="return confirm('Are you sure you want to delete this comment?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit" 
                                    class="p-2 rounded-lg bg-red-900/20 hover:bg-red-900/50 text-red-400 hover:text-red-200 transition-colors duration-150"
                                    title="Delete"
                                >
                                    <i data-feather="trash-2" class="h-4 w-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined') {
        feather.replace({
            'stroke-width': 1.5
        });
    }
});
</script>

@endsection