<x-layout
    title="Edit {{ $idea->title }}"
    >
    <div class="mt-10 flex flex-col items-center text-white">

        <x-card
            class="w-fit max-w-2xl"
            header="Edit Idea"
            textAlign="left"
            bgColor="#1f2937"
            textColor="#f9fafb"
        >
            <form
                action="/ideas/{{ $idea->id }}"
                method="POST"
                class="space-y-5"
            >
                @csrf
                @method('PUT')

                <x-idea.title :value="$idea->title" />
                <x-idea.state :value="$idea->state->value" />
                <x-idea.description :value="$idea->description" />

                {{-- Actions --}}
                <div class="flex items-center justify-center gap-3 pt-1">

                    {{-- Cancel --}}
                    <a
                        href="/ideas/{{ $idea->id }}"
                        class="group inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white shadow-lg backdrop-blur-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-white/20 hover:bg-white/10"
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

                        Cancel
                    </a>

                    {{-- Save --}}
                    <button
                        type="submit"
                        class="group inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition-all duration-200 hover:-translate-y-0.5 hover:bg-indigo-500"
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
