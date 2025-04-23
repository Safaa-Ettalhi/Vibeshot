@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center">
        <div class="flex items-center space-x-1 text-sm">
            
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-3 py-2 rounded-md bg-gray-800/50 text-gray-500 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="ml-1">Previous</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 rounded-md bg-gray-800/50 text-gray-300 hover:bg-gray-700/50 hover:text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="ml-1">Previous</span>
                </a>
            @endif

           
            @foreach ($elements as $element)
                
                @if (is_string($element))
                    <span class="relative inline-flex items-center px-3 py-2 rounded-md bg-gray-800/50 text-gray-500">
                        {{ $element }}
                    </span>
                @endif

               
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="relative inline-flex items-center px-3 py-2 rounded-md bg-blue-500 text-white font-semibold">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="relative inline-flex items-center px-3 py-2 rounded-md bg-gray-800/50 text-gray-300 hover:bg-gray-700/50 hover:text-white transition-all duration-200">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 rounded-md bg-gray-800/50 text-gray-300 hover:bg-gray-700/50 hover:text-white transition-all duration-200">
                    <span class="mr-1">Next</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span class="relative inline-flex items-center px-3 py-2 rounded-md bg-gray-800/50 text-gray-500 cursor-not-allowed">
                    <span class="mr-1">Next</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif