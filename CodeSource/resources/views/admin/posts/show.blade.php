@extends('admin.layouts.app')

@section('title', 'Détails de la publication')

@section('header', 'Détails de la publication')

@section('header-actions')
<div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
   
    <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#222222] hover:bg-[#333333] text-white font-medium rounded-lg transition-colors w-full sm:w-auto">
        <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i> Retour
    </a>
</div>
@endsection


@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2">
      
        <div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm overflow-hidden mb-6">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-800/50">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : asset('images/default-avatar.svg') }}" 
                             alt="{{ $post->user->name }}" 
                             class="w-10 h-10 sm:w-11 sm:h-11 rounded-full object-cover border border-gray-800">
                        <div>
                            <div class="font-medium text-white">{{ $post->user->name }}</div>
                            <div class="text-xs text-gray-400">{{ '@' . $post->user->username }}</div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-400 flex items-center">
                        <i data-feather="clock" class="w-4 h-4 mr-1.5"></i>
                        {{ $post->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
            
            <div class="p-4 sm:p-6">
                @if($post->caption)
                    <div class="text-white mb-6 text-base sm:text-lg">{{ $post->caption }}</div>
                @endif
                
                @if($post->images->count() > 0)
                   
                    <div class="relative">
                        <div class="overflow-x-auto pb-4 scrollbar-hide">
                            <div class="flex space-x-4 min-w-full">
                                @foreach($post->images as $image)
                                    <div class="flex-none w-[80%] sm:w-[60%] md:w-[45%] rounded-xl overflow-hidden shadow-md border border-gray-800/50">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Post image" class="w-full h-auto object-cover aspect-square">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        

                        @if($post->images->count() > 1)
                            <div class="flex justify-center mt-3 gap-1.5">
                                @foreach($post->images as $index => $image)
                                    <div class="w-2 h-2 rounded-full bg-gray-600"></div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @elseif($post->image_path)
                    <div class="rounded-xl overflow-hidden shadow-md border border-gray-800/50">
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post image" class="w-full h-auto object-cover">
                    </div>
                @endif
            </div>
            
            <div class="px-4 sm:px-6 py-4 border-t border-gray-800/50">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                        <div class="flex items-center gap-2 text-gray-400">
                            <div class="bg-red-500/10 p-1.5 rounded-lg">
                                <i data-feather="heart" class="w-5 h-5 {{ $post->likes->count() > 0 ? 'text-red-500' : 'text-gray-500' }}"></i>
                            </div>
                            <span class="font-medium">{{ $post->likes->count() }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-400">
                            <div class="bg-blue-500/10 p-1.5 rounded-lg">
                                <i data-feather="message-circle" class="w-5 h-5 {{ $post->comments->count() > 0 ? 'text-blue-500' : 'text-gray-500' }}"></i>
                            </div>
                            <span class="font-medium">{{ $post->comments->count() }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-400">
                            <div class="bg-green-500/10 p-1.5 rounded-lg">
                                <i data-feather="repeat" class="w-5 h-5 {{ $post->shares->count() > 0 ? 'text-green-500' : 'text-gray-500' }}"></i>
                            </div>
                            <span class="font-medium">{{ $post->shares->count() }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <a href="{{ route('posts.show', $post) }}" target="_blank" 
                           class="inline-flex items-center px-3 py-1.5 bg-[#222222] hover:bg-[#333333] text-white text-sm font-medium rounded-lg transition-colors">
                            <i data-feather="external-link" class="w-4 h-4 mr-1.5"></i> Voir sur le site
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm overflow-hidden">
            <div class="px-4 sm:px-6 py-5 border-b border-gray-800/50">
                <h3 class="font-semibold text-lg text-white flex items-center">
                    <i data-feather="message-circle" class="w-5 h-5 mr-2 text-blue-500"></i>
                    Commentaires ({{ $post->comments->count() }})
                </h3>
            </div>
            
            <div>
                @if($post->comments->count() > 0)
                    <div class="divide-y divide-gray-800/50">
                        @foreach($post->comments as $comment)
                            <div class="p-4 sm:p-5 hover:bg-[#161616] transition-colors">
                                <div class="flex items-start gap-3">
                                    <img src="{{ $comment->user->profile_image ? asset('storage/' . $comment->user->profile_image) : asset('images/default-avatar.svg') }}" 
                                         alt="{{ $comment->user->name }}" 
                                         class="w-9 h-9 rounded-full object-cover border border-gray-800">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                            <div>
                                                <a href="{{ route('admin.users.show', $comment->user) }}" class="font-medium text-white hover:text-blue-400 transition-colors truncate">
                                                    {{ $comment->user->name }}
                                                </a>
                                                <span class="text-xs text-gray-500 ml-2">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="self-end sm:self-auto">
                                                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="inline" 
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-lg transition-colors">
                                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="text-gray-300 mt-2 break-words">{{ $comment->content }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center text-gray-400">
                        <div class="bg-[#191919]/60 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="message-circle" class="w-8 h-8 text-gray-600"></i>
                        </div>
                        <p>Aucun commentaire pour cette publication.</p>
                    </div>
                @endif
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
                        <div class="text-sm font-medium text-gray-400 mb-2">Type</div>
                        <div class="bg-[#191919] px-4 py-3 rounded-lg flex flex-wrap items-center gap-2">
                            @if($post->original_post_id)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-700/50 text-gray-300">
                                    <i data-feather="repeat" class="w-3 h-3 mr-1"></i> Partagé
                                </span>
                                <a href="{{ route('admin.posts.show', $post->originalPost) }}" class="text-blue-500 hover:text-blue-400 text-sm flex items-center">
                                    Voir l'original <i data-feather="chevron-right" class="w-3 h-3 ml-1"></i>
                                </a>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-500">
                                    <i data-feather="file" class="w-3 h-3 mr-1"></i> Original
                                </span>
                                @if($post->shares->count() > 0)
                                    <span class="text-sm text-gray-400">
                                        Partagé {{ $post->shares->count() }} fois
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-sm font-medium text-gray-400 mb-2">Auteur</div>
                        <div class="bg-[#191919] px-4 py-3 rounded-lg">
                            <a href="{{ route('admin.users.show', $post->user) }}" class="flex items-center gap-2 hover:text-blue-400 transition-colors text-white">
                                <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : asset('images/default-avatar.svg') }}" 
                                     alt="{{ $post->user->name }}" 
                                     class="w-6 h-6 rounded-full object-cover border border-gray-700">
                                <span>{{ $post->user->name }}</span>
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-sm font-medium text-gray-400 mb-2">Date de création</div>
                        <div class="bg-[#191919] px-4 py-3 rounded-lg text-white flex items-center">
                            <i data-feather="calendar" class="w-4 h-4 mr-2 text-gray-500"></i>
                            {{ $post->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-sm font-medium text-gray-400 mb-2">Dernière modification</div>
                        <div class="bg-[#191919] px-4 py-3 rounded-lg text-white flex items-center">
                            <i data-feather="clock" class="w-4 h-4 mr-2 text-gray-500"></i>
                            {{ $post->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
     
    </div>
</div>

<div class="flex flex-col sm:flex-row sm:justify-between gap-4 mt-8">
    <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#222222] hover:bg-[#333333] text-white font-medium rounded-lg transition-colors">
        <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i> Retour à la liste
    </a>
    
</div>

<style>

.scrollbar-hide {
  scrollbar-width: none; 
  -ms-overflow-style: none; }

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