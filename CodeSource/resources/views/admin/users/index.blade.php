@extends('admin.layouts.app')

@section('title', 'User Management')
@section('header-actions')
<div class="relative">
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center rounded-full border border-gray-600  px-4 py-2 ">
        <i data-feather="search" class="text-gray-400 mr-2"></i>
        <input 
            type="text" 
            name="search" 
            placeholder="Search for a user..." 
            value="{{ request('search') }}"
            class="bg-transparent focus:outline-none text-white placeholder-gray-400 w-full"
        >
    </form>
</div>
@endsection

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold text-white">User Management</h1>    
    
    <form action="{{ route('admin.users.index') }}" method="GET"
        class="flex items-center rounded-full border border-gray-600 px-4 py-2 w-full sm:w-auto">
        <i data-feather="search" class="text-gray-400 mr-2"></i>
        <input 
            type="text" 
            name="search" 
            placeholder="Search for a user..." 
            value="{{ request('search') }}"
            class="bg-transparent focus:outline-none text-white placeholder-gray-400 w-full"
        >
    </form>
</div>

<div class="bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-[#0a0a0a]">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Registration Date</th>
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
                                User
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($user->is_blocked)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-500">
                                Blocked
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-500">
                                Active
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-sm">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="inline-flex items-center p-1.5 bg-[#222222] hover:bg-[#333333] text-gray-300 hover:text-white rounded-lg transition-colors"
                               title="View profile">
                                <i data-feather="eye" class="w-4 h-4"></i>
                            </a>
                          
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center p-1.5 {{ $user->is_admin ? 'bg-gray-600 hover:bg-gray-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white rounded-lg transition-colors"
                                            title="{{ $user->is_admin ? 'Remove admin rights' : 'Grant admin rights' }}">
                                        <i data-feather="{{ $user->is_admin ? 'user' : 'shield' }}" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            
                                @if($user->is_blocked)
                                    <form action="{{ route('admin.users.unblock', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center p-1.5 bg-green-600/80 hover:bg-green-700 text-white rounded-lg transition-colors"
                                                title="Unblock user">
                                            <i data-feather="unlock" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.block', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center p-1.5 bg-yellow-600/80 hover:bg-yellow-700 text-white rounded-lg transition-colors"
                                                title="Block user">
                                            <i data-feather="lock" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this user? This action is irreversible.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center p-1.5 bg-red-600/80 hover:bg-red-700 text-white rounded-lg transition-colors"
                                            title="Delete user">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @else
                                <span class="inline-flex items-center p-1.5 bg-[#222222] text-gray-500 rounded-lg cursor-not-allowed" title="You cannot modify your own status">
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