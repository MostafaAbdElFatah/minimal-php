<a
    href="{{ url('/ideas/' . $idea->id) }}"
    class="group relative block h-full overflow-hidden rounded-2xl border border-base-300/30 bg-base-100 p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-xl"
>
    {{-- Glow --}}
    <div
        class="pointer-events-none absolute -right-16 -top-16 h-32 w-32 rounded-full bg-primary/10 blur-3xl transition-all duration-500 group-hover:bg-primary/20"
    ></div>

    <div class="relative flex h-full flex-col">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4">

            {{-- Title --}}
            <h2
                class="min-w-0 truncate text-base font-semibold tracking-tight text-base-content transition-colors duration-300 group-hover:text-primary"
            >
                {{ $idea->title }}
            </h2>

        <x-idea.idea-status :idea="$idea"  />

        </div>

        {{-- Description --}}
        <p
            class="mt-3 line-clamp-2 text-xs leading-5 text-base-content/60 transition-colors duration-300 group-hover:text-base-content/80"
        >
            {{ $idea->description }}
        </p>

        {{-- Footer --}}
        <div
            class="mt-5 flex items-center justify-between border-t border-base-300/30 pt-4"
        >
            <span
                class="text-[10px] font-medium uppercase tracking-widest text-base-content/40"
            >
                Idea
            </span>

            <span
                class="flex items-center gap-1 text-xs font-medium text-base-content/50 transition-all duration-300 group-hover:gap-2 group-hover:text-primary"
            >
                View

                <span
                    class="transition-transform duration-300 group-hover:translate-x-1"
                >
                    →
                </span>
            </span>
        </div>

    </div>
</a>