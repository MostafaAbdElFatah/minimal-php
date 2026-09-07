@if ($paginator->hasPages())
    <nav
        class="flex max-w-full items-center gap-1 rounded-full border border-base-300 bg-base-100/90 p-1.5 text-sm text-base-content shadow-xl backdrop-blur-md"
        aria-label="Pagination"
    >
        <span class="hidden px-3 text-xs font-medium text-base-content/60 sm:inline">
            {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }}
            <span class="text-base-content/35">of</span>
            {{ $paginator->total() }}
        </span>

        @if ($paginator->onFirstPage())
            <span
                class="flex h-9 w-9 items-center justify-center rounded-full text-base-content/30"
                aria-disabled="true"
            >
                <span aria-hidden="true">&lsaquo;</span>
                <span class="sr-only">Previous page</span>
            </span>
        @else
            <a
                href="{{ $paginator->previousPageUrl() }}"
                rel="prev"
                class="flex h-9 w-9 items-center justify-center rounded-full text-base-content/60 transition hover:bg-base-200 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/40"
                aria-label="Previous page"
            >
                <span aria-hidden="true">&lsaquo;</span>
            </a>
        @endif

        <div class="hidden items-center gap-1 sm:flex">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="flex h-9 w-7 items-center justify-center text-base-content/40">
                        &hellip;
                    </span>
                @elseif (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="flex h-9 min-w-9 items-center justify-center rounded-full bg-primary px-3 font-semibold text-primary-content shadow-sm"
                                aria-current="page"
                            >
                                {{ $page }}
                            </span>
                        @else
                            <a
                                href="{{ $url }}"
                                class="flex h-9 min-w-9 items-center justify-center rounded-full px-3 text-base-content/70 transition hover:bg-base-200 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/40"
                                aria-label="Go to page {{ $page }}"
                            >
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        <span class="px-3 font-medium sm:hidden">
            {{ $paginator->currentPage() }}
            <span class="text-base-content/40">/</span>
            {{ $paginator->lastPage() }}
        </span>

        @if ($paginator->hasMorePages())
            <a
                href="{{ $paginator->nextPageUrl() }}"
                rel="next"
                class="flex h-9 w-9 items-center justify-center rounded-full text-base-content/60 transition hover:bg-base-200 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/40"
                aria-label="Next page"
            >
                <span aria-hidden="true">&rsaquo;</span>
            </a>
        @else
            <span
                class="flex h-9 w-9 items-center justify-center rounded-full text-base-content/30"
                aria-disabled="true"
            >
                <span aria-hidden="true">&rsaquo;</span>
                <span class="sr-only">Next page</span>
            </span>
        @endif
    </nav>
@endif
