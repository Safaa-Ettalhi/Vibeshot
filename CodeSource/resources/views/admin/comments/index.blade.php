@extends('admin.layouts.app')

@section('title', 'Gestion des commantaires')

@section('header', 'Gestion des commantaires')

@section('header-actions')
<div class="relative">
    <form action="{{ route('admin.comments.index') }}" method="GET" class="search-input">
        <i data-feather="search"></i>
        <input type="text" name="search" placeholder="Rechercher un commantaire..." value="{{ request('search') }}">
    </form>
</div>
@endsection

@section('content')
<div class="bg-[#111111] rounded-xl border border-gray-800 shadow-lg overflow-hidden backdrop-blur-sm">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-800 bg-[#111111]/90">
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Auteur</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Commentaire</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Publication</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @foreach($comments as $comment)
                <tr class="hover:bg-[#1a1a1a] transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img 
                                    src="{{ $comment->user->profile_image ? asset('storage/' . $comment->user->profile_image) : asset('images/default-avatar.svg') }}" 
                                    alt="{{ $comment->user->name }}" 
                                    class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-800"
                                >
                            </div>
                            <div class="flex flex-col">
                                <span class="font-medium text-white">{{ $comment->user->name }}</span>
                                <span class="text-xs text-gray-400">{{ '@' . $comment->user->username }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="max-w-xs text-gray-300 text-sm truncate">{{ $comment->content }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 h-10 w-10 rounded-lg overflow-hidden">
                                @if($comment->post->image_path)
                                    <img src="{{ asset('storage/' . $comment->post->image_path) }}" alt="Post" class="h-10 w-10 object-cover">
                                @elseif($comment->post->images->count() > 0)
                                    <img src="{{ asset('storage/' . $comment->post->images->first()->image_path) }}" alt="Post" class="h-10 w-10 object-cover">
                                @else
                                    <div class="h-10 w-10 bg-[#1a1a1a] flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('admin.posts.show', $comment->post) }}" class="text-sm text-gray-300 hover:text-blue-400 transition-colors duration-150 max-w-[150px] truncate">
                                {{ Str::limit($comment->post->caption, 20) }}
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            {{ $comment->created_at->format('d/m/Y H:i') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a 
                                href="{{ route('admin.comments.show', $comment) }}" 
                                class="p-2 rounded-lg bg-[#1a1a1a] hover:bg-[#222222] text-gray-300 hover:text-white transition-colors duration-150"
                                title="Voir le détail"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </a>
                            
                            <form 
                                action="{{ route('admin.comments.destroy', $comment) }}" 
                                method="POST" 
                                class="inline" 
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit" 
                                    class="p-2 rounded-lg bg-red-900/20 hover:bg-red-900/50 text-red-400 hover:text-red-200 transition-colors duration-150"
                                    title="Supprimer"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 bg-[#0a0a0a] border-t border-gray-800">
        <div class="pagination-container">
            {{ $comments->links() }}
        </div>
    </div>
</div>


@endsection