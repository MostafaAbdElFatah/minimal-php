<x-layout title="Ideas">
    <form method="POST" action="/ideas/create">
        @csrf

        <div>
            <h2 class="text-base/7 font-semibold text-white">New Idea</h2>

            <p class="mt-1 text-sm/6 text-gray-400">
                This information will be displayed publicly so be careful what you share.
            </p>

            <div class="mt-6 list-">

                <x-idea.title />
                <x-idea.state />
                <x-idea.description />
            </div>
        </div>

        <div class="mt-4 border-b border-white/10 pb-4"></div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button
                type="button"
                onclick="history.back()"
                class="text-sm/6 font-semibold text-white"
            >
                Cancel
            </button>

            <button
                type="submit"
                class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
            >
                Save
            </button>
        </div>
    </form>
</x-layout>
