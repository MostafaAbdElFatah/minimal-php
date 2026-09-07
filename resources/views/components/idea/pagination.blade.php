@props(['paginator'])

@php
    $paginator->appends(request()->query());
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $pages = collect([1, $currentPage - 1, $currentPage, $currentPage + 1, $lastPage])
        ->filter(fn ($page) => $page > 0 && $page <= $lastPage)
        ->unique()
        ->sort()
        ->values();
@endphp

@if ($paginator->hasPages())
    <nav
        class="flex max-w-full items-center gap-1 rounded-full border border-base-300 bg-base-100/90 p-1.5 text-sm text-base-content shadow-xl backdrop-blur-md"
        aria-label="Pagination"
    >
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
                class="flex h-9 w-9 items-center justify-center rounded-full text-base-content/60 transition hover:bg-base-200 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/40"
                aria-label="Previous page"
            >
                <span aria-hidden="true">&lsaquo;</span>
            </a>
        @endif

        <div class="hidden items-center gap-1 sm:flex">
            @php($previousPage = null)

            @foreach ($pages as $page)
                @if ($previousPage !== null && $page > $previousPage + 1)
                    <span class="flex h-9 w-7 items-center justify-center text-base-content/40">
                        &hellip;
                    </span>
                @endif

                @if ($page === $currentPage)
                    <span
                        class="flex h-9 min-w-9 items-center justify-center rounded-full bg-primary px-3 font-semibold text-primary-content shadow-sm"
                        aria-current="page"
                    >
                        {{ $page }}
                    </span>
                @else
                    <a
                        href="{{ $paginator->url($page) }}"
                        class="flex h-9 min-w-9 items-center justify-center rounded-full px-3 text-base-content/70 transition hover:bg-base-200 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/40"
                    >
                        {{ $page }}
                    </a>
                @endif

                @php($previousPage = $page)
            @endforeach
        </div>

        <span class="px-3 font-medium sm:hidden">
            {{ $currentPage }}
            <span class="text-base-content/40">/</span>
            {{ $lastPage }}
        </span>

        @if ($paginator->hasMorePages())
            <a
                href="{{ $paginator->nextPageUrl() }}"
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
