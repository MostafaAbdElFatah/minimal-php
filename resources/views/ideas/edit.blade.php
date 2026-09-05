<x-layout
    title="Edit {{ $idea->title }}"
>
    <div class="mt-10 flex justify-center px-4 pb-10">

        <x-card
            class="w-full max-w-2xl"
            header="Edit Idea"
            textAlign="left"
            bgColor="oklch(var(--b1))"
            textColor="oklch(var(--bc))"
        >
            <div class="mb-6">
                <p class="text-xs font-medium uppercase tracking-[0.2em] text-primary/70">
                    Idea Management
                </p>

                <p class="mt-2 text-sm text-base-content/50">
                    Update your idea details and save your changes.
                </p>
            </div>

            <form
                action="/ideas/{{ $idea->id }}"
                method="POST"
                class="space-y-6"
            >
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <x-idea.title :value="$idea->title" />
                    <x-idea.state :value="$idea->state->value" />
                    <x-idea.description :value="$idea->description" />
                </div>

                {{-- Actions --}}
                <div class="mt-8 flex flex-wrap items-center justify-end gap-3 border-t border-base-300/30 pt-6">

                    {{-- Cancel --}}
                    <a
                        href="/ideas/{{ $idea->id }}"
                        class="group inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-base-300/40 bg-base-200/40 px-4 py-2.5 text-sm font-semibold text-base-content shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-base-300 hover:bg-base-200"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
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

                        Cancel
                    </a>

                    {{-- Save --}}
                    <button
                        type="submit"
                        class="group inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-primary/20 bg-primary/10 px-5 py-2.5 text-sm font-semibold text-primary shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/40 hover:bg-primary hover:text-primary-content hover:shadow-md hover:shadow-primary/10"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 transition-transform duration-200 group-hover:scale-110"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                        Save Changes
                    </button>

                </div>
            </form>
        </x-card>
    </div>
</x-layout>