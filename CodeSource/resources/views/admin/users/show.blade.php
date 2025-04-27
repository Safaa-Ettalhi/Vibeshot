@extends('admin.layouts.app')

@section('title', 'User Details')
@section('content')
<div class="mb-6">
    
<h1 class="text-2xl font-bold text-white">User Details</h1>
 </div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm overflow-hidden md:col-span-1">
        <div class="p-6 flex flex-col items-center text-center">
            <div class="relative mb-5">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" 
                         class="w-28 h-28 rounded-full object-cover border-4 border-[#191919] shadow-lg">
                @else
                    <div class="w-28 h-28 rounded-full bg-[#191919] flex items-center justify-center shadow-lg">
                        <span class="text-3xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                @endif
                
                @if($user->is_admin)
                    <div class="absolute bottom-1 right-1 bg-blue-500 rounded-full p-1.5 border-2 border-[#111111] shadow-lg">
                        <i data-feather="shield" class="w-4 h-4 text-white"></i>
                    </div>
                @endif
            </div>
            
            <h2 class="text-2xl font-bold text-white mb-1">{{ $user->name }}</h2>
            <p class="text-gray-400 mb-4">{{ '@' . $user->username }}</p>
            
            <div class="flex justify-center gap-6 mb-6">
                <div class="text-center">
                    <div class="text-xl font-bold text-white">{{ $user->posts->count() }}</div>
                    <div class="text-xs text-gray-400 uppercase tracking-wide">Posts</div>
                </div>
                <div class="text-center">
                    <div class="text-xl font-bold text-white">{{ $user->followers->count() }}</div>
                    <div class="text-xs text-gray-400 uppercase tracking-wide">Followers</div>
                </div>
                <div class="text-center">
                    <div class="text-xl font-bold text-white">{{ $user->following->count() }}</div>
                    <div class="text-xs text-gray-400 uppercase tracking-wide">Following</div>
                </div>
            </div>
            
            <div class="w-full pt-5 border-t border-gray-800/50">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-500/10 p-2 rounded-lg">
                            <i data-feather="mail" class="text-blue-500 w-5 h-5"></i>
                        </div>
                        <span class="text-gray-300">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-green-500/10 p-2 rounded-lg">
                            <i data-feather="calendar" class="text-green-500 w-5 h-5"></i>
                        </div>
                        <span class="text-gray-300">Registered on {{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="md:col-span-2">
       
        <div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-gray-800/50">
                <h3 class="font-semibold text-lg text-white flex items-center">
                    <i data-feather="user" class="w-5 h-5 mr-2 text-blue-500"></i>
                    Information
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm font-medium text-gray-400 mb-2">Name</div>
                        <div class="text-white bg-[#191919] px-4 py-3 rounded-lg">{{ $user->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-400 mb-2">Username</div>
                        <div class="text-white bg-[#191919] px-4 py-3 rounded-lg">{{ $user->username }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-400 mb-2">Email</div>
                        <div class="text-white bg-[#191919] px-4 py-3 rounded-lg">{{ $user->email }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-400 mb-2">Role</div>
                        <div class="bg-[#191919] px-4 py-3 rounded-lg">
                            @if($user->is_admin)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-500">
                                    <i data-feather="shield" class="w-3 h-3 mr-1"></i> Admin
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-700/50 text-gray-300">
                                    <i data-feather="user" class="w-3 h-3 mr-1"></i> User
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-gray-400 mb-2">Bio</div>
                        <div class="text-white bg-[#191919] px-4 py-3 rounded-lg min-h-[80px]">
                            {{ $user->bio ?? 'No biography' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
     
        <div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm overflow-hidden">
            <div class="px-6 py-5 flex justify-between items-center border-b border-gray-800/50">
                <h3 class="font-semibold text-lg text-white flex items-center">
                    <i data-feather="image" class="w-5 h-5 mr-2 text-green-500"></i>
                    Recent Posts
                </h3>
                <a href="{{ route('admin.posts.index') }}?user={{ $user->id }}" class="text-green-500 hover:text-green-400 text-sm flex items-center transition-colors">
                    View all
                    <i data-feather="chevron-right" class="ml-1 w-4 h-4"></i>
                </a>
            </div>
            <div class="p-0">
                @if($user->posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
                        @foreach($user->posts->take(4) as $post)
                            <div class="bg-[#191919] rounded-xl overflow-hidden border border-gray-800/50 hover:border-gray-700/50 transition-all duration-300 group">
                                <div class="flex items-center gap-4 p-4">
                                    @if($post->image_path)
                                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post" class="w-16 h-16 rounded-lg object-cover border border-gray-800">
                                    @elseif($post->images->count() > 0)
                                        <img src="{{ asset('storage/' . $post->images->first()->image_path) }}" alt="Post" class="w-16 h-16 rounded-lg object-cover border border-gray-800">
                                    @else
                                        <div class="w-16 h-16 rounded-lg bg-[#222222] flex items-center justify-center border border-gray-800">
                                            <i data-feather="image" class="text-gray-600 w-6 h-6"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-white truncate">{{ Str::limit($post->caption, 50) ?: 'No caption' }}</div>
                                        <div class="flex items-center gap-3 mt-2">
                                            <span class="inline-flex items-center text-xs text-gray-400">
                                                <i data-feather="heart" class="w-3 h-3 mr-1 text-red-500"></i> {{ $post->likes->count() }}
                                            </span>
                                            <span class="inline-flex items-center text-xs text-gray-400">
                                                <i data-feather="message-circle" class="w-3 h-3 mr-1 text-blue-500"></i> {{ $post->comments->count() }}
                                            </span>
                                            <span class="text-xs text-gray-500">{{ $post->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.posts.show', $post) }}" class="text-gray-400 hover:text-white p-2 bg-gray-800/50 hover:bg-gray-700/70 rounded-lg transition-colors group-hover:bg-blue-600/20 group-hover:text-blue-500">
                                        <i data-feather="external-link" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center text-gray-400">
                        <div class="bg-[#191919]/60 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="image" class="w-8 h-8 text-gray-600"></i>
                        </div>
                        <p>This user has not posted any content yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="flex flex-col md:flex-row md:justify-between gap-4 mt-8">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#222222] hover:bg-[#333333] text-white font-medium rounded-lg transition-colors">
        <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i> Back to list
    </a>
    <div class="flex flex-col md:flex-row gap-3">
        <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 {{ $user->is_admin ? 'bg-gray-600 hover:bg-gray-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white font-medium rounded-lg transition-colors">
                <i data-feather="{{ $user->is_admin ? 'user' : 'shield' }}" class="w-4 h-4 mr-2"></i>
                {{ $user->is_admin ? 'Remove admin rights' : 'Make administrator' }}
            </button>
        </form>
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action is irreversible.');" class="w-full md:w-auto">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                <i data-feather="trash-2" class="w-4 h-4 mr-2"></i> Delete
            </button>
        </form>
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