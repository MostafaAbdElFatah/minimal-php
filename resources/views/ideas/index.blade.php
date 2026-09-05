<x-layout title="Ideas" class="bg-gray-700 p-5 max-w-4xl mx-auto" :showNav="false">

    <div class="mt-10 flex items-center justify-between text-white">
        <h2 class="font-bold text-lg">Your Ideas</h2>

        <a href="/ideas/create"
           class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
            + Create New Idea
        </a>
    </div>

    @if ($ideas->count() > 0)
        <div class="text-white">
            <ul class="mt-4 list-disc list-inside text-sm/6 text-gray-400">
                @foreach ($ideas as $idea)
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

                @endforeach
            </ul>
        </div>
    @endif


    @if (session('success'))
        <div
            id="success-message"
            class="fixed bottom-5 right-5 z-50 rounded-lg bg-green-600 px-5 py-3 text-white shadow-lg"
        >
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                const message = document.getElementById('success-message');

                if (message) {
                    message.remove();
                }
            }, 10000);
        </script>
    @endif
</x-layout>