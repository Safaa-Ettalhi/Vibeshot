@extends('layouts.app')

@section('content')
<div class="container">
   
    <div class="relative mb-36">
       
        <div class="w-full h-[300px] bg-cover bg-center rounded-b-[30px]" 
             style="background-image: url('{{ auth()->user()->cover_image ? asset('storage/' . auth()->user()->cover_image) : asset('images/default-cover.png') }}')">
        </div>
        
        <div class="absolute bottom-0 left-8 transform translate-y-1/2 z-10">
            <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-avatar.svg') }}" 
                 alt="{{ auth()->user()->name }}" 
                 class="w-[100px] h-[100px] rounded-full border-4 border-[#0c0d0e] bg-[#0c0d0e] object-cover shadow-md">
        </div>

        <div class="absolute left-5 top-[350px] z-10">
            <div>
                <h1 class="text-xl font-bold text-white mb-1">{{ auth()->user()->name }}</h1>
                <div class="text-[#3498db] text-sm font-medium">{{ auth()->user()->followers()->count() }} followers</div>
            </div>
        </div>
    </div>

    <div class="px-4 mb-6">
        <h2 class="text-xl font-semibold">Your Saved Posts ({{ $bookmarkedPosts->count() }})</h2>
       
    </div>

   
    <div class="grid grid-flow-col auto-cols-max gap-4 overflow-x-auto px-4 pb-6 hide-scrollbar snap-x snap-mandatory">
        @forelse($bookmarkedPosts as $bookmark)
            <div class="relative w-[280px] h-[280px] rounded-xl overflow-hidden snap-start">
                <a href="{{ route('posts.show', $bookmark->post) }}">
                    <img src="{{ asset('storage/' . $bookmark->post->image_path) }}" alt="Saved post" class="w-full h-full object-cover">
                </a>
                
              
                <div class="absolute top-3 left-3 py-1 pl-1 pr-3 text-sm text-white flex items-center gap-2">
                    <img src="{{ $bookmark->post->user->profile_image ? asset('storage/' . $bookmark->post->user->profile_image) : asset('images/default-avatar.svg') }}" 
                         alt="{{ $bookmark->post->user->name }}" 
                         class="w-10 h-10 rounded-full object-cover">
                    {{'@' . $bookmark->post->user->username }}
                </div>
                
               
                <form action="{{ route('bookmarks.destroy', $bookmark->post) }}" method="POST" class="unsave-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="absolute bottom-3 left-3 flex items-center gap-1 bg-black bg-opacity-50 hover:bg-opacity-70 rounded-full py-1 px-3 text-xs text-white transition-all cursor-pointer">
                        <i data-feather="bookmark" class="w-3 h-3 text-white fill-current"></i>
                        <span>saved 
                            @php
                                $now = \Carbon\Carbon::now();
                                $created = $bookmark->created_at;
                                
                                $diff = $now->diff($created);
                                
                                if ($diff->y > 0) {
                                    echo $diff->y . ' years ago';
                                } elseif ($diff->m > 0) {
                                    echo $diff->m . ' months ago';
                                } elseif ($diff->d > 0) {
                                    echo $diff->d . ' days ago';
                                } elseif ($diff->h > 0) {
                                    echo $diff->h . ' hours ago';
                                } elseif ($diff->i > 0) {
                                    echo $diff->i . ' min ago';
                                } else {
                                    echo 'now';
                                }
                            @endphp
                        </span>
                    </button>
                </form>
            </div>
        @empty
            <div class="col-span-4 py-12 text-center w-full">
                <div class="text-6xl mb-4">🔖</div>
                <h3 class="text-xl font-semibold mb-2">No saved posts yet</h3>
                <p class="text-gray-400">When you bookmark posts, they'll appear here.</p>
                <a href="{{ route('home') }}" class="mt-4 inline-block py-2 px-4 bg-[#3498db] text-white rounded-full font-semibold hover:bg-[#2980b9] transition">
                    Explore posts
                </a>
            </div>
        @endforelse
    </div>
    
    
    <div class="px-4 mt-4">
        {{ $bookmarkedPosts->links() }}
    </div>
</div>

<style>
    
    .hide-scrollbar {
        -ms-overflow-style: none; 
        scrollbar-width: none; 
    }
    .hide-scrollbar::-webkit-scrollbar {
        display: none;  
    }
    .unsave-form button {
        transition: all 0.2s ease;
    }
    
    .unsave-form button:hover {
        background-color: rgba(0, 0, 0, 0.7);
    }

    .unsave-form button svg {
        color: white;
        fill: white;
    }
    .unsave-form button:hover {
        transform: scale(1.05);
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace({
                'stroke-width': 1.5
            });
        }
        const unsaveButtons = document.querySelectorAll('.unsave-form button');
        unsaveButtons.forEach(button => {
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute bottom-12 left-3 bg-black bg-opacity-80 text-white text-xs py-1 px-2 rounded opacity-0 transition-opacity duration-200';
            tooltip.textContent = 'Click to unsave';
            tooltip.style.pointerEvents = 'none';

            button.parentNode.appendChild(tooltip);

            button.addEventListener('mouseenter', () => {
                tooltip.classList.remove('opacity-0');
                tooltip.classList.add('opacity-100');
            });

            button.addEventListener('mouseleave', () => {
                tooltip.classList.remove('opacity-100');
                tooltip.classList.add('opacity-0');
            });
        });
    });
</script>
@endpush
@endsection

