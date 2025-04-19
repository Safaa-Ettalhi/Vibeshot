@extends('admin.layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('header', 'Gestion des utilisateurs')

@section('header-actions')
<div class="relative">
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center rounded-full border border-gray-600  px-4 py-2 ">
        <i data-feather="search" class="text-gray-400 mr-2"></i>
        <input 
            type="text" 
            name="search" 
            placeholder="Rechercher un utilisateur..." 
            value="{{ request('search') }}"
            class="bg-transparent focus:outline-none text-white placeholder-gray-400 w-full"
        >
    </form>
</div>
@endsection

@section('content')
<div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-[#0a0a0a]">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Utilisateur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Rôle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date d'inscription</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/30">
                @foreach($users as $user)
                <tr class="hover:bg-[#161616] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-avatar.svg') }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-10 h-10 rounded-full object-cover border border-gray-800">
                            <div>
                                <div class="font-medium text-white">{{ $user->name }}</div>
                                <div class="text-xs text-gray-400">{{ '@' . $user->username }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-300">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @if($user->is_admin)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-blue-500">
                                Admin
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700/50 text-gray-300">
                                Utilisateur
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($user->is_blocked)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-500">
                                Bloqué
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-500">
                                Actif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-sm">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="inline-flex items-center p-1.5 bg-[#222222] hover:bg-[#333333] text-gray-300 hover:text-white rounded-lg transition-colors"
                               title="Voir le profil">
                                <i data-feather="eye" class="w-4 h-4"></i>
                            </a>
                          
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center p-1.5 {{ $user->is_admin ? 'bg-gray-600 hover:bg-gray-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white rounded-lg transition-colors"
                                            title="{{ $user->is_admin ? 'Retirer les droits admin' : 'Donner les droits admin' }}">
                                        <i data-feather="{{ $user->is_admin ? 'user' : 'shield' }}" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            
                                @if($user->is_blocked)
                                    <form action="{{ route('admin.users.unblock', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center p-1.5 bg-green-600/80 hover:bg-green-700 text-white rounded-lg transition-colors"
                                                title="Débloquer l'utilisateur">
                                            <i data-feather="unlock" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.block', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center p-1.5 bg-yellow-600/80 hover:bg-yellow-700 text-white rounded-lg transition-colors"
                                                title="Bloquer l'utilisateur">
                                            <i data-feather="lock" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center p-1.5 bg-red-600/80 hover:bg-red-700 text-white rounded-lg transition-colors"
                                            title="Supprimer l'utilisateur">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @else
                                <span class="inline-flex items-center p-1.5 bg-[#222222] text-gray-500 rounded-lg cursor-not-allowed" title="Vous ne pouvez pas modifier votre propre statut">
                                    <i data-feather="shield" class="w-4 h-4"></i>
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-800/50">
        <div class="pagination">
            {{ $users->links() }}
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

.pagination {
    display: flex;
    justify-content: center;
}

.pagination > nav {
    display: inline-flex;
}

.pagination .flex.justify-between {
    display: none;
}

.pagination .relative.inline-flex.items-center {
    margin: 0 2px;
}

.pagination .relative.inline-flex.items-center a,
.pagination .relative.inline-flex.items-center span {
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

.pagination a {
    background-color: rgba(255, 255, 255, 0.05);
    color: #e0e0e0;
}

.pagination a:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.pagination span.bg-blue-50 {
    background-color: rgba(59, 130, 246, 0.2) !important;
    color: #3b82f6;
}

.pagination .text-gray-500 {
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
</style>
@endsection