@extends('admin.layouts.app')

@section('title', 'Détails du commentaire')

@section('header', 'Détails du commentaire')

@section('header-actions')
<div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
    <a href="{{ route('admin.comments.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#222222] hover:bg-[#333333] text-white font-medium rounded-lg transition-colors w-full sm:w-auto">
        <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i> Retour
    </a>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2">
       
        <div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm overflow-hidden mb-6">
            <div class="px-4 sm:px-6 py-5 border-b border-gray-800/50">
                <h3 class="font-semibold text-lg text-white flex items-center">
                    <i data-feather="message-circle" class="w-5 h-5 mr-2 text-blue-500"></i>
                    Contenu du commentaire
                </h3>
            </div>
            <div class="p-4 sm:p-6">
                <div class="bg-[#191919] rounded-lg p-4 border border-gray-800">
                    <div class="flex items-start gap-3">
                        <img src="{{ $comment->user->profile_image ? asset('storage/' . $comment->user->profile_image) : asset('images/default-avatar.svg') }}" 
                             alt="{{ $comment->user->name }}" 
                             class="w-10 h-10 rounded-full object-cover border border-gray-800 shrink-0">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <span class="font-medium text-white">{{ $comment->user->name }}</span>
                                    <span class="text-xs text-gray-400 ml-2">{{ '@' . $comment->user->username }}</span>
                                </div>
                                <div class="text-sm text-gray-400 flex items-center">
                                    <i data-feather="clock" class="w-3.5 h-3.5 mr-1"></i>
                                    {{ $comment->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <div class="text-gray-300 mt-3 break-words">{{ $comment->content }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
       
        <div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm overflow-hidden">
            <div class="px-4 sm:px-6 py-5 border-b border-gray-800/50">
                <h3 class="font-semibold text-lg text-white flex items-center">
                    <i data-feather="file-text" class="w-5 h-5 mr-2 text-green-500"></i>
                    Publication associée
                </h3>
            </div>
            <div class="p-4 sm:p-6">
                <div class="bg-[#191919] rounded-lg overflow-hidden border border-gray-800">
                    <div class="p-4 flex items-center justify-between border-b border-gray-800/50">
                        <div class="flex items-center gap-3">
                            <img src="{{ $comment->post->user->profile_image ? asset('storage/' . $comment->post->user->profile_image) : asset('images/default-avatar.svg') }}" 
                                 alt="{{ $comment->post->user->name }}" 
                                 class="w-10 h-10 rounded-full object-cover border border-gray-800">
                            <div>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-1">
                                    <span class="font-medium text-white">{{ $comment->post->user->name }}</span>
                                    <span class="text-gray-400 text-sm">{{ '@' . $comment->post->user->username }}</span>
                                    <span class="hidden sm:inline text-gray-500 px-1">·</span>
                                    <span class="text-gray-400 text-xs">{{ $comment->post->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        @if($comment->post->caption)
                            <div class="p-4 text-gray-300">{{ $comment->post->caption }}</div>
                        @endif
                        
                        @if($comment->post->images->count() > 0)
                            <div class="px-4 pt-1 pb-4">
                            
                                <div class="relative">
                                    <div class="overflow-x-auto pb-4 scrollbar-hide">
                                        <div class="flex space-x-4 min-w-full">
                                            @foreach($comment->post->images as $image)
                                                <div class="flex-none w-[80%] sm:w-[60%] rounded-xl overflow-hidden shadow-md border border-gray-800/50">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Post image" class="w-full h-auto object-cover aspect-square">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    @if($comment->post->images->count() > 1)
                                        <div class="flex justify-center mt-2 gap-1.5">
                                            @foreach($comment->post->images as $index => $image)
                                                <div class="w-2 h-2 rounded-full bg-gray-600"></div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif($comment->post->image_path)
                            <div class="px-4 pt-1 pb-4">
                                <div class="w-full rounded-xl overflow-hidden shadow-md border border-gray-800/50">
                                    <img src="{{ asset('storage/' . $comment->post->image_path) }}" alt="Post image" class="w-full h-auto object-cover">
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-4 border-t border-gray-800/50">
                        <div class="flex items-center gap-6 text-gray-400">
                            <div class="flex items-center gap-2">
                                <div class="bg-red-500/10 p-1 rounded-lg">
                                    <i data-feather="heart" class="w-4 h-4 {{ $comment->post->likes->count() > 0 ? 'text-red-500' : 'text-gray-500' }}"></i>
                                </div>
                                <span>{{ $comment->post->likes->count() }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="bg-blue-500/10 p-1 rounded-lg">
                                    <i data-feather="message-circle" class="w-4 h-4 {{ $comment->post->comments->count() > 0 ? 'text-blue-500' : 'text-gray-500' }}"></i>
                                </div>
                                <span>{{ $comment->post->comments->count() }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="bg-green-500/10 p-1 rounded-lg">
                                    <i data-feather="repeat" class="w-4 h-4 {{ $comment->post->shares->count() > 0 ? 'text-green-500' : 'text-gray-500' }}"></i>
                                </div>
                                <span>{{ $comment->post->shares->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 flex justify-end">
                    <a href="{{ route('admin.posts.show', $comment->post) }}" class="inline-flex items-center px-4 py-2 bg-[#222222] hover:bg-[#333333] text-white text-sm font-medium rounded-lg transition-colors">
                        <i data-feather="external-link" class="w-4 h-4 mr-2"></i> Voir la publication complète
                    </a>
                </div>
            </div>
        </div>
    </div>
    
   
    <div class="lg:col-span-1">
        <div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm overflow-hidden mb-6">
            <div class="px-4 sm:px-6 py-5 border-b border-gray-800/50">
                <h3 class="font-semibold text-lg text-white flex items-center">
                    <i data-feather="info" class="w-5 h-5 mr-2 text-blue-500"></i>
                    Informations
                </h3>
            </div>
            <div class="p-4 sm:p-6">
                <div class="space-y-5">
                   
                    
                    <div>
                        <div class="text-sm font-medium text-gray-400 mb-2">Auteur</div>
                        <div class="bg-[#191919] px-4 py-3 rounded-lg">
                            <a href="{{ route('admin.users.show', $comment->user) }}" class="flex items-center gap-2 hover:text-blue-400 transition-colors text-white">
                                <img src="{{ $comment->user->profile_image ? asset('storage/' . $comment->user->profile_image) : asset('images/default-avatar.svg') }}" 
                                     alt="{{ $comment->user->name }}" 
                                     class="w-6 h-6 rounded-full object-cover border border-gray-700">
                                <span>{{ $comment->user->name }}</span>
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-sm font-medium text-gray-400 mb-2">Publication</div>
                        <div class="bg-[#191919] px-4 py-3 rounded-lg">
                            <a href="{{ route('admin.posts.show', $comment->post) }}" class="flex items-center gap-2 hover:text-blue-400 transition-colors text-white">
                                @if($comment->post->image_path)
                                    <img src="{{ asset('storage/' . $comment->post->image_path) }}" alt="Post" class="w-6 h-6 rounded-lg object-cover border border-gray-700">
                                @elseif($comment->post->images->count() > 0)
                                    <img src="{{ asset('storage/' . $comment->post->images->first()->image_path) }}" alt="Post" class="w-6 h-6 rounded-lg object-cover border border-gray-700">
                                @else
                                    <div class="w-6 h-6 rounded-lg bg-gray-800 flex items-center justify-center">
                                        <i data-feather="image" class="text-gray-600 w-3 h-3"></i>
                                    </div>
                                @endif
                                <span class="truncate">{{ Str::limit($comment->post->caption, 20) ?: 'Sans légende' }}</span>
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-sm font-medium text-gray-400 mb-2">Date de création</div>
                        <div class="bg-[#191919] px-4 py-3 rounded-lg text-white flex items-center">
                            <i data-feather="calendar" class="w-4 h-4 mr-2 text-gray-500"></i>
                            {{ $comment->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-sm font-medium text-gray-400 mb-2">Dernière modification</div>
                        <div class="bg-[#191919] px-4 py-3 rounded-lg text-white flex items-center">
                            <i data-feather="clock" class="w-4 h-4 mr-2 text-gray-500"></i>
                            {{ $comment->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="flex flex-col sm:flex-row sm:justify-between gap-4 mt-8">
    <a href="{{ route('admin.comments.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#222222] hover:bg-[#333333] text-white font-medium rounded-lg transition-colors">
        <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i> Retour à la liste
    </a>
    <div>
        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" 
              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ? Cette action est irréversible.');" class="w-full sm:w-auto">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                <i data-feather="trash-2" class="w-4 h-4 mr-2"></i> Supprimer
            </button>
        </form>
    </div>
</div>

<style>
.scrollbar-hide {
  scrollbar-width: none; 
  -ms-overflow-style: none; 
}

.scrollbar-hide::-webkit-scrollbar {
  display: none; 
  width: 0;
  height: 0;
}
</style>

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