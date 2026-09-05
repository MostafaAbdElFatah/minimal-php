<x-layout title="Ideas" class="bg-gray-700 p-5 max-w-4xl mx-auto" :showNav="false">

    <div class="mt-10 flex items-center justify-between text-white">
        <h2 class="font-bold text-lg">Your Ideas</h2>

        <div class="flex items-center gap-2">
            
            <a
                href="/ideas/create"
                class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
            >
                + Create New Idea
            </a>

            @if ($ideas->count() > 0)
                <form
                    method="POST"
                    action="{{ route('ideas.destroy-all') }}"
                    onsubmit="return confirm('Are you sure you want to delete all ideas? This action cannot be undone.')"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="rounded bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500"
                    >
                        Delete All Ideas
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="mt-2">
        <form method="GET" action="/">
            <label
                for="state"
                class="text-xs font-semibold uppercase tracking-wider text-gray-500"
            >
                Filter by state
            </label>

            <div class="mt-2 flex items-center gap-2">
                <select
                    id="state"
                    name="state"
                    onchange="this.form.submit()"
                    class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-sm text-white outline-none focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20"
                >
                    <option value="" class="bg-gray-800">
                        All states
                    </option>

                    @foreach (\App\Enums\IdeaState::cases() as $state)
                        <option
                            value="{{ $state->value }}"
                            class="bg-gray-800"
                            @selected(request('state') === $state->value)
                        >
                            {{ $state }}
                        </option>
                    @endforeach
                </select>

                @if (request('state'))
                    <a
                        href="/"
                        class="rounded-lg px-3 py-2 text-sm text-gray-400 transition hover:bg-white/5 hover:text-white"
                    >
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="text-white">
        <ul class="mt-4 list-disc list-inside text-sm/6 text-gray-400">

            @forelse ($ideas as $idea)

                <li>
                    <a
                        href="/ideas/{{ $idea->id }}"
                        class="group inline-flex items-center gap-2 text-{{ $idea->color }}-400 hover:text-indigo-400 transition-colors duration-200"
                    >
                        <span>
                            {{ $idea->title }}
                        </span>

                        {{-- State shown on hover --}}
                        <span
                            class="text-xs font-medium opacity-0 transition-all duration-200 group-hover:opacity-100"
                        >
                            ({{ $idea->state }})
                        </span>
                    </a>
                </li>

        @empty

            <li class="list-none py-10 text-center">
                <div class="flex flex-col items-center gap-3">

                    @if (request('state'))

                        <svg
                            class="h-10 w-10 text-gray-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 6.75h18M6.75 6.75v11.25A2.25 2.25 0 009 20.25h6a2.25 2.25 0 002.25-2.25V6.75M9 10.5v6m6-6v6"
                            />
                        </svg>

                        <p class="text-sm font-medium text-gray-400">
                            No ideas found
                        </p>

                        <p class="text-xs text-gray-500">
                            There are no ideas with the
                            <span class="text-gray-400">{{ request('state') }}</span>
                            state.
                        </p>

                        <a
                            href="/"
                            class="mt-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-500"
                        >
                            Clear Filter
                        </a>

                    @else

                        <svg
                            class="h-10 w-10 text-gray-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 0H6.375A3.375 3.375 0 003 5.625v12.75A3.375 3.375 0 006.375 21.75h11.25A3.375 3.375 0 0021 18.375V16.5m-13.5-9V4.5m0 3h3m-3 3h6m-6 3h6"
                            />
                        </svg>

                        <p class="text-sm font-medium text-gray-400">
                            No ideas yet
                        </p>

                        <p class="text-xs text-gray-500">
                            Create your first idea to get started.
                        </p>

                        <a
                            href="/ideas/create"
                            class="mt-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-500"
                        >
                            + Create Your First Idea
                        </a>

                    @endif

                </div>
            </li>

        @endforelse

        </ul>
    </div>

    @if (session('success'))
        <div
            id="success-message"
            class="fixed bottom-5 right-5 z-50 rounded-lg bg-green-600 px-5 py-3 text-white shadow-lg"
        >
            {{ session('success') }}
        </div>

        <script>
            window.addEventListener('pageshow', (event) => {
                if (event.persisted) {
                    document.getElementById('success-message')?.remove();
                }
            });
        </script>
    @endif

</x-layout>
