<x-layout title="{{ $idea->title }}">
    <div class="mt-6 flex flex-col items-center text-base-content">

        {{-- Idea Card --}}
        <x-card
            class="w-fit max-w-2xl"
            header="{{ $idea->title }}"
            textAlign="left"
            bgColor="oklch(var(--b1))"
            textColor="oklch(var(--bc))"
        >
            {{-- Status & Date --}}
            <div class="mb-6 flex items-center justify-between gap-8">

                <x-idea.idea-status :idea="$idea" />

                <span class="text-xs text-base-content/50">
                    {{ $idea->created_at?->format('M d, Y • h:i A') ?? 'Not available' }}
                </span>

            </div>

            {{-- Description --}}
            <div class="rounded-xl border border-base-300/30 bg-base-200/30 p-5">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-base-content/50">
                    Description
                </h2>

                <p class="mt-3 text-sm leading-7 text-base-content/80">
                    {{ $idea->description }}
                </p>
            </div>

            {{-- Actions --}}
            <div class="mt-6 flex flex-wrap items-center justify-center gap-3">

                {{-- Back --}}
                <a
                    href="/"
                    class="group inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-base-300/40 bg-base-200/50 px-4 py-2.5 text-sm font-semibold text-base-content shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:border-base-300 hover:bg-base-200"
                >
                    <svg
                        class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-1"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>

                    Back
                </a>

                {{-- Edit --}}
                <a
                    href="/ideas/{{ $idea->id }}/edit"
                    class="group inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-primary/20 bg-primary/10 px-4 py-2.5 text-sm font-semibold text-primary shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/40 hover:bg-primary hover:text-primary-content hover:shadow-md hover:shadow-primary/10"
                >
                    <svg
                        class="h-4 w-4 transition-transform duration-200 group-hover:rotate-[-8deg]"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"
                        />
                    </svg>

                    Edit
                </a>

                {{-- Delete --}}
                <form
                    action="/ideas/{{ $idea->id }}"
                    method="POST"
                    class="shrink-0"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        onclick="return confirm('Are you sure you want to delete this idea?')"
                        class="group inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-error/20 bg-error/10 px-4 py-2.5 text-sm font-semibold text-error shadow-lg shadow-error/5 transition-all duration-200 hover:-translate-y-0.5 hover:border-error/40 hover:bg-error hover:text-error-content"
                    >
                        <svg
                            class="h-4 w-4 transition-transform duration-200 group-hover:scale-110"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6V7M9 7h6"
                            />
                        </svg>

                        Delete
                    </button>
                </form>

            </div>
        </x-card>
    </div>
</x-layout>