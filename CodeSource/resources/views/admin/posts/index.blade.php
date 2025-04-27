@extends('admin.layouts.app')

@section('title', 'Posts Management')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="text-xl sm:text-2xl font-bold text-white">Posts Management</h1>   
    <form action="{{ route('admin.posts.index') }}" method="GET" class="flex items-center rounded-full border border-gray-600 px-3 sm:px-4 py-1.5 sm:py-2 w-full sm:w-auto">
        <i data-feather="search" class="text-gray-400 w-4 h-4 mr-2"></i>
        <input 
            type="text" 
            name="search" 
            placeholder="Search for a post..." 
            value="{{ request('search') }}"
            class="bg-transparent focus:outline-none text-white placeholder-gray-400 w-full text-sm"
        >
    </form>
</div>
<div class="admin-card bg-[#111111] rounded-xl border border-gray-800/50 shadow-lg overflow-hidden">

    <!-- Mobile view -->
    <div class="block md:hidden p-3 sm:p-4">
        @foreach($posts as $post)
        <div class="bg-[#111111] border border-gray-800 rounded-lg shadow p-3 sm:p-4 mb-4">
            <div class="flex items-start gap-3 mb-3">
                @if($post->image_path)
                    <img src="{{ asset('storage/' . $post->image_path) }}?t={{ time() }}" alt="Post" class="w-14 h-14 sm:w-16 sm:h-16 rounded-lg object-cover">
                @elseif($post->images->count() > 0)
                    <img src="{{ asset('storage/' . $post->images->first()->image_path) }}?t={{ time() }}" alt="Post" class="w-14 h-14 sm:w-16 sm:h-16 rounded-lg object-cover">
                @else
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-lg bg-gray-700 flex items-center justify-center">
                        <i data-feather="image" class="text-gray-500 w-5 h-5"></i>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <h3 class="text-white font-semibold text-sm truncate">{{ Str::limit($post->caption, 50) }}</h3>
                    <div class="flex items-center gap-2 mt-1 text-gray-400 text-xs sm:text-sm">
                        <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ $post->user->name }}" class="w-5 h-5 sm:w-6 sm:h-6 rounded-full object-cover">
                        <span class="truncate max-w-[120px]">{{ $post->user->name }}</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between text-xs sm:text-sm text-gray-400 gap-2 mb-3">
                <div>
                    <span class="font-medium text-white">{{ $post->original_post_id ? 'Shared' : 'Original' }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="flex items-center gap-1"><i data-feather="heart" class="w-3 h-3"></i> {{ $post->likes->count() }}</span>
                    <span class="flex items-center gap-1"><i data-feather="message-circle" class="w-3 h-3"></i> {{ $post->comments->count() }}</span>
                    <span class="flex items-center gap-1"><i data-feather="repeat" class="w-3 h-3"></i> {{ $post->shares->count() }}</span>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs mb-3">
                <div class="text-gray-500">
                    {{ $post->created_at->format('d/m/Y H:i') }}
                </div>
                <div>
                    @if($post->is_hidden)
                        <span class="admin-badge admin-badge-danger">Hidden</span>
                    @else
                        <span class="admin-badge admin-badge-success">Visible</span>
                    @endif
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.posts.show', $post) }}" class="admin-btn admin-btn-secondary admin-btn-sm" title="View">
                    <i data-feather="eye" class="w-4 h-4"></i>
                </a>
                
                @if($post->is_hidden)
                    <form action="{{ route('admin.posts.unhide', $post) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="admin-btn admin-btn-success admin-btn-sm" title="Make visible">
                            <i data-feather="eye" class="w-4 h-4"></i>
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.posts.hide', $post) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="admin-btn admin-btn-warning admin-btn-sm" title="Hide">
                            <i data-feather="eye-off" class="w-4 h-4"></i>
                        </button>
                    </form>
                @endif
              
                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm" title="Delete">
                        <i data-feather="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Desktop view -->
    <div class="hidden md:block overflow-x-auto">
        <div class="admin-card-body p-0 min-w-full">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#0a0a0a]">
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Post</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Author</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Engagement</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-4 lg:px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/30">
                    @foreach($posts as $post)
                    <tr class="hover:bg-[#161616] transition-colors">
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                @if($post->image_path)
                                    <img src="{{ asset('storage/' . $post->image_path) }}?t={{ time() }}" alt="Post" class="w-10 h-10 lg:w-12 lg:h-12 rounded-lg object-cover">
                                @elseif($post->images->count() > 0)
                                    <img src="{{ asset('storage/' . $post->images->first()->image_path) }}?t={{ time() }}" alt="Post" class="w-10 h-10 lg:w-12 lg:h-12 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-lg bg-gray-800 flex items-center justify-center">
                                        <i data-feather="image" class="text-gray-600 w-5 h-5"></i>
                                    </div>
                                @endif
                                <div class="font-medium text-white truncate max-w-[120px] md:max-w-[150px] lg:max-w-[200px]">{{ Str::limit($post->caption, 40) }}</div>
                            </div>
                        </td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ $post->user->name }}" class="w-7 h-7 lg:w-8 lg:h-8 rounded-full object-cover">
                                <div class="text-gray-300 truncate max-w-[100px]">{{ $post->user->name }}</div>
                            </div>
                        </td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                            @if($post->original_post_id)
                                <span class="admin-badge admin-badge-secondary">Shared</span>
                            @else
                                <span class="admin-badge admin-badge-primary">Original</span>
                            @endif
                        </td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                            @if($post->is_hidden)
                                <span class="admin-badge admin-badge-danger">Hidden</span>
                            @else
                                <span class="admin-badge admin-badge-success">Visible</span>
                            @endif
                        </td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3 text-gray-400 text-sm">
                                <span class="flex items-center gap-1"><i data-feather="heart" class="w-3 h-3"></i> {{ $post->likes->count() }}</span>
                                <span class="flex items-center gap-1"><i data-feather="message-circle" class="w-3 h-3"></i> {{ $post->comments->count() }}</span>
                                <span class="flex items-center gap-1"><i data-feather="repeat" class="w-3 h-3"></i> {{ $post->shares->count() }}</span>
                            </div>
                        </td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-gray-400 text-sm">{{ $post->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.posts.show', $post) }}" class="admin-btn admin-btn-secondary admin-btn-sm" title="View">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </a>
                                
                                @if($post->is_hidden)
                                    <form action="{{ route('admin.posts.unhide', $post) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="admin-btn admin-btn-success admin-btn-sm" title="Make visible">
                                            <i data-feather="eye" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.posts.hide', $post) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="admin-btn admin-btn-warning admin-btn-sm" title="Hide">
                                            <i data-feather="eye-off" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                              
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm" title="Delete">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
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

<style>
.admin-btn {
    padding: 8px 12px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.admin-btn-primary {
    background-color: #3a86ff;
    color: white;
}

.admin-btn-primary:hover {
    background-color: #2a75e6;
    transform: translateY(-1px);
}

.admin-btn-secondary {
    background-color: rgba(255, 255, 255, 0.1);
    color: var(--text-primary, #ffffff);
}

.admin-btn-secondary:hover {
    background-color: rgba(255, 255, 255, 0.15);
    transform: translateY(-1px);
}

.admin-btn-danger {
    background-color: #e74c3c;
    color: white;
}

.admin-btn-danger:hover {
    background-color: #c0392b;
    transform: translateY(-1px);
}

.admin-btn-success {
    background-color: #2ecc71;
    color: white;
}

.admin-btn-success:hover {
    background-color: #27ae60;
    transform: translateY(-1px);
}

.admin-btn-warning {
    background-color: #f39c12;
    color: white;
}

.admin-btn-warning:hover {
    background-color: #d35400;
    transform: translateY(-1px);
}

.admin-btn-sm {
    padding: 5px 10px;
    font-size: 0.75rem;
}

.admin-badge {
    padding: 3px 8px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}

@media (min-width: 640px) {
    .admin-badge {
        padding: 4px 10px;
        font-size: 0.75rem;
        gap: 4px;
    }
}

.admin-badge-primary {
    background-color: rgba(58, 134, 255, 0.15);
    color: #3a86ff;
}

.admin-badge-secondary {
    background-color: rgba(255, 255, 255, 0.1);
    color: var(--text-secondary, #a0aec0);
}

.admin-badge-danger {
    background-color: rgba(231, 76, 60, 0.15);
    color: #e74c3c;
}

.admin-badge-success {
    background-color: rgba(46, 204, 113, 0.15);
    color: #2ecc71;
}

.admin-badge-warning {
    background-color: rgba(241, 196, 15, 0.15);
    color: #f1c40f;
}
</style>
@endsection