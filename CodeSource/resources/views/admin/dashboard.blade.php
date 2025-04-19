@extends('admin.layouts.app')

@section('title', 'Tableau de bord')

@section('header', 'Tableau de bord')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
   
    <div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm hover:shadow-blue-900/10 hover:border-gray-700/50 transition-all duration-300">
        <div class="p-6 flex items-center">
            <div class="bg-blue-500/10 p-4 rounded-xl mr-5">
                <i data-feather="users" class="text-blue-500 w-6 h-6"></i>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-400 mb-1">Utilisateurs</div>
                <div class="text-3xl font-bold text-white">{{ number_format($stats['users_count']) }}</div>
            </div>
            <div class="ml-auto text-blue-500/70">
                <i data-feather="trending-up" class="w-5 h-5"></i>
            </div>
        </div>
    </div>
    
   
    <div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm hover:shadow-green-900/10 hover:border-gray-700/50 transition-all duration-300">
        <div class="p-6 flex items-center">
            <div class="bg-green-500/10 p-4 rounded-xl mr-5">
                <i data-feather="image" class="text-green-500 w-6 h-6"></i>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-400 mb-1">Publications</div>
                <div class="text-3xl font-bold text-white">{{ number_format($stats['posts_count']) }}</div>
            </div>
            <div class="ml-auto text-green-500/70">
                <i data-feather="trending-up" class="w-5 h-5"></i>
            </div>
        </div>
    </div>
    
    
    <div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm hover:shadow-purple-900/10 hover:border-gray-700/50 transition-all duration-300">
        <div class="p-6 flex items-center">
            <div class="bg-purple-500/10 p-4 rounded-xl mr-5">
                <i data-feather="message-circle" class="text-purple-500 w-6 h-6"></i>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-400 mb-1">Commentaires</div>
                <div class="text-3xl font-bold text-white">{{ number_format($stats['comments_count']) }}</div>
            </div>
            <div class="ml-auto text-purple-500/70">
                <i data-feather="trending-up" class="w-5 h-5"></i>
            </div>
        </div>
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
    
    <div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm overflow-hidden">
        <div class="px-6 py-5 flex justify-between items-center border-b border-gray-800/50">
            <h3 class="font-semibold text-lg text-white flex items-center">
                <i data-feather="users" class="w-5 h-5 mr-2 text-blue-500"></i>
                Utilisateurs récents
            </h3>
            <a href="{{ route('admin.users.index') }}" class="text-blue-500 hover:text-blue-400 text-sm flex items-center transition-colors">
                Voir tous
                <i data-feather="chevron-right" class="ml-1 w-4 h-4"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#0a0a0a]">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/30">
                    @foreach($stats['recent_users'] as $user)
                    <tr class="hover:bg-[#161616] transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ $user->name }}" class="w-9 h-9 rounded-full object-cover border border-gray-800">
                                <div>
                                    <div class="font-medium text-white">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ '@' . $user->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-300">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center px-3 py-1.5 bg-[#222222] hover:bg-[#333333] text-white text-xs font-medium rounded-full transition-colors">
                                <span>Voir</span>
                                <i data-feather="eye" class="ml-1 w-3.5 h-3.5"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
   
    <div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm overflow-hidden">
        <div class="px-6 py-5 flex justify-between items-center border-b border-gray-800/50">
            <h3 class="font-semibold text-lg text-white flex items-center">
                <i data-feather="image" class="w-5 h-5 mr-2 text-green-500"></i>
                Publications récentes
            </h3>
            <a href="{{ route('admin.posts.index') }}" class="text-green-500 hover:text-green-400 text-sm flex items-center transition-colors">
                Voir toutes
                <i data-feather="chevron-right" class="ml-1 w-4 h-4"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#0a0a0a]">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Publication</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Auteur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/30">
                    @foreach($stats['recent_posts'] as $post)
                    <tr class="hover:bg-[#161616] transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($post->image_path)
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post" class="w-10 h-10 rounded-lg object-cover border border-gray-800">
                                @elseif($post->images->count() > 0)
                                    <img src="{{ asset('storage/' . $post->images->first()->image_path) }}" alt="Post" class="w-10 h-10 rounded-lg object-cover border border-gray-800">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-[#191919] flex items-center justify-center border border-gray-800">
                                        <i data-feather="image" class="text-gray-500 w-5 h-5"></i>
                                    </div>
                                @endif
                                <div class="font-medium text-white truncate max-w-[150px]">{{ Str::limit($post->caption, 30) ?: 'Sans légende' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-300">{{ $post->user->name }}</td>
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $post->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.posts.show', $post) }}" class="inline-flex items-center px-3 py-1.5 bg-[#222222] hover:bg-[#333333] text-white text-xs font-medium rounded-full transition-colors">
                                <span>Voir</span>
                                <i data-feather="eye" class="ml-1 w-3.5 h-3.5"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm overflow-hidden mb-8">
    <div class="px-6 py-5 flex justify-between items-center border-b border-gray-800/50">
        <h3 class="font-semibold text-lg text-white flex items-center">
            <i data-feather="message-circle" class="w-5 h-5 mr-2 text-purple-500"></i>
            Commentaires récents
        </h3>
        <a href="{{ route('admin.comments.index') }}" class="text-purple-500 hover:text-purple-400 text-sm flex items-center transition-colors">
            Voir tous
            <i data-feather="chevron-right" class="ml-1 w-4 h-4"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-[#0a0a0a]">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Auteur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Commentaire</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Publication</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/30">
                @foreach($stats['recent_comments'] as $comment)
                <tr class="hover:bg-[#161616] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $comment->user->profile_image ? asset('storage/' . $comment->user->profile_image) : asset('images/default-avatar.svg') }}" alt="{{ $comment->user->name }}" class="w-9 h-9 rounded-full object-cover border border-gray-800">
                            <div class="font-medium text-white">{{ $comment->user->name }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-300 truncate max-w-[300px]">{{ $comment->content }}</td>
                    <td class="px-6 py-4 text-gray-400 truncate max-w-[150px]">{{ Str::limit($comment->post->caption, 20) ?: 'Sans légende' }}</td>
                    <td class="px-6 py-4 text-gray-400 text-sm">{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.comments.show', $comment) }}" class="inline-flex items-center px-3 py-1.5 bg-[#222222] hover:bg-[#333333] text-white text-xs font-medium rounded-full transition-colors">
                                <span>Voir</span>
                                <i data-feather="eye" class="ml-1 w-3.5 h-3.5"></i>
                            </a>
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