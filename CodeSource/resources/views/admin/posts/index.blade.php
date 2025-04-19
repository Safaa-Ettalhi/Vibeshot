@extends('admin.layouts.app')

@section('title', 'Gestion des publications')

@section('header', 'Gestion des publications')

@section('header-actions')
<div class="relative">
    <form action="{{ route('admin.posts.index') }}" method="GET" class="flex items-center rounded-full border border-gray-600  px-4 py-2 ">
        <i data-feather="search" class="text-gray-400 mr-2"></i>
        <input 
            type="text" 
            name="search" 
            placeholder="Rechercher une publication..." 
            value="{{ request('search') }}"
            class="bg-transparent focus:outline-none text-white placeholder-gray-400 w-full"
        >
    </form>
</div>
@endsection


@section('content')
<div class="admin-card bg-[#111111]">

    {{-- Version mobile (cartes) --}}
    <div class="block md:hidden p-4">
        @foreach($posts as $post)
        <div class="bg-[#111111] border border-gray-800 rounded-lg shadow p-4 mb-4">
            <div class="flex items-start gap-3 mb-3">
            @if($post->image_path)
    <img src="{{ asset('storage/' . $post->image_path) }}?t={{ time() }}" alt="Post" class="w-16 h-16 rounded-lg object-cover">
@elseif($post->images->count() > 0)
    <img src="{{ asset('storage/' . $post->images->first()->image_path) }}?t={{ time() }}" alt="Post" class="w-16 h-16 rounded-lg object-cover">
@else
    <div class="w-16 h-16 rounded-lg bg-gray-700 flex items-center justify-center">
        <i data-feather="image" class="text-gray-500 w-5 h-5"></i>
    </div>
@endif
                <div class="flex-1">
                    <h3 class="text-white font-semibold text-sm truncate">{{ Str::limit($post->caption, 50) }}</h3>
                    <div class="flex items-center gap-2 mt-1 text-gray-400 text-sm">
                        <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ $post->user->name }}" class="w-6 h-6 rounded-full object-cover">
                        {{ $post->user->name }}
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between text-sm text-gray-400 gap-2 mb-3">
                <div>
                    <span class="font-medium text-white">{{ $post->original_post_id ? 'Partagé' : 'Original' }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="flex items-center gap-1"><i data-feather="heart" class="w-3 h-3"></i> {{ $post->likes->count() }}</span>
                    <span class="flex items-center gap-1"><i data-feather="message-circle" class="w-3 h-3"></i> {{ $post->comments->count() }}</span>
                    <span class="flex items-center gap-1"><i data-feather="repeat" class="w-3 h-3"></i> {{ $post->shares->count() }}</span>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs mb-3">
                <div class="text-gray-500">
                    Publié le {{ $post->created_at->format('d/m/Y H:i') }}
                </div>
                <div>
                    @if($post->is_hidden)
                        <span class="admin-badge admin-badge-danger">Masqué</span>
                    @else
                        <span class="admin-badge admin-badge-success">Visible</span>
                    @endif
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.posts.show', $post) }}" class="admin-btn admin-btn-secondary admin-btn-sm">
                    <i data-feather="eye" class="w-4 h-4"></i>
                </a>
                
               
                @if($post->is_hidden)
                    <form action="{{ route('admin.posts.unhide', $post) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="admin-btn admin-btn-success admin-btn-sm" title="Rendre visible">
                            <i data-feather="eye" class="w-4 h-4"></i>
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.posts.hide', $post) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="admin-btn admin-btn-warning admin-btn-sm" title="Masquer">
                            <i data-feather="eye-off" class="w-4 h-4"></i>
                        </button>
                    </form>
                @endif
              
                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm">
                        <i data-feather="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="hidden md:block">
        <div class="admin-card-body p-0">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Publication</th>
                        <th>Auteur</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Engagement</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                            @if($post->image_path)
    <img src="{{ asset('storage/' . $post->image_path) }}?t={{ time() }}" alt="Post" class="w-12 h-12 rounded-lg object-cover">
@elseif($post->images->count() > 0)
    <img src="{{ asset('storage/' . $post->images->first()->image_path) }}?t={{ time() }}" alt="Post" class="w-12 h-12 rounded-lg object-cover">
@else
    <div class="w-12 h-12 rounded-lg bg-gray-800 flex items-center justify-center">
        <i data-feather="image" class="text-gray-600 w-5 h-5"></i>
    </div>
@endif
                                <div class="font-medium text-white truncate max-w-[200px]">{{ Str::limit($post->caption, 40) }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ $post->user->name }}" class="w-8 h-8 rounded-full object-cover">
                                <div class="text-gray-300">{{ $post->user->name }}</div>
                            </div>
                        </td>
                        <td>
                            @if($post->original_post_id)
                                <span class="admin-badge admin-badge-secondary">Partagé</span>
                            @else
                                <span class="admin-badge admin-badge-primary">Original</span>
                            @endif
                        </td>
                        <td>
                            @if($post->is_hidden)
                                <span class="admin-badge admin-badge-danger">Masqué</span>
                            @else
                                <span class="admin-badge admin-badge-success">Visible</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-3 text-gray-400 text-sm">
                                <span class="flex items-center gap-1"><i data-feather="heart" class="w-3 h-3"></i> {{ $post->likes->count() }}</span>
                                <span class="flex items-center gap-1"><i data-feather="message-circle" class="w-3 h-3"></i> {{ $post->comments->count() }}</span>
                                <span class="flex items-center gap-1"><i data-feather="repeat" class="w-3 h-3"></i> {{ $post->shares->count() }}</span>
                            </div>
                        </td>
                        <td class="text-gray-400 text-sm">{{ $post->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.posts.show', $post) }}" class="admin-btn admin-btn-secondary admin-btn-sm">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </a>
                                
                               
                                @if($post->is_hidden)
                                    <form action="{{ route('admin.posts.unhide', $post) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="admin-btn admin-btn-success admin-btn-sm" title="Rendre visible">
                                            <i data-feather="eye" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.posts.hide', $post) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="admin-btn admin-btn-warning admin-btn-sm" title="Masquer">
                                            <i data-feather="eye-off" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                              
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm">
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

    <div class="admin-card-footer">
        <div class="admin-pagination">
            {{ $posts->links() }}
        </div>
    </div>
</div>

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500/90 text-white px-4 py-2 rounded-lg shadow-lg flex items-center space-x-2 z-50 animate-fade-in-up">
        <i data-feather="check-circle" class="w-5 h-5"></i>
        <span>{{ session('success') }}</span>
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.animate-fade-in-up').classList.add('animate-fade-out-down');
        }, 3000);
    </script>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500/90 text-white px-4 py-2 rounded-lg shadow-lg flex items-center space-x-2 z-50 animate-fade-in-up">
        <i data-feather="alert-circle" class="w-5 h-5"></i>
        <span>{{ session('error') }}</span>
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.animate-fade-in-up').classList.add('animate-fade-out-down');
        }, 3000);
    </script>
@endif

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
.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out forwards;
}

.animate-fade-out-down {
    animation: fadeOutDown 0.3s ease-out forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeOutDown {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(20px);
    }
}

.admin-pagination {
    display: flex;
    justify-content: center;
}

.admin-pagination > nav {
    display: inline-flex;
}

.admin-pagination .flex.justify-between {
    display: none;
}

.admin-pagination .relative.inline-flex.items-center {
    margin: 0 2px;
}

.admin-pagination .relative.inline-flex.items-center a,
.admin-pagination .relative.inline-flex.items-center span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 10px;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.2s;
}

.admin-pagination a {
    background-color: rgba(255, 255, 255, 0.05);
    color: #e0e0e0;
}

.admin-pagination a:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.admin-pagination span.bg-blue-50 {
    background-color: rgba(59, 130, 246, 0.2) !important;
    color: #3b82f6;
}

.admin-pagination .text-gray-500 {
    color: rgba(255, 255, 255, 0.3);
}

.search-input {
    position: relative;
    width: 300px;
}

.search-input i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    width: 16px;
    height: 16px;
}

.search-input input {
    width: 100%;
    background-color: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
    padding: 8px 12px 8px 36px;
    border-radius: 8px;
    transition: all 0.2s;
}

.search-input input:focus {
    outline: none;
    border-color: rgba(59, 130, 246, 0.5);
    background-color: rgba(255, 255, 255, 0.08);
}

.search-input input::placeholder {
    color: #6b7280;
}

.admin-btn {
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
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
    padding: 6px 12px;
    font-size: 0.875rem;
}

.admin-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
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