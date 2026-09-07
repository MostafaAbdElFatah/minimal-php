<x-layout title="Ideas">

    <div class="mt-10 flex items-center justify-between text-white">
        <h2 class="text-lg font-bold tracking-tight text-base-content">
            Your <span class="text-primary">Ideas</span>
            <span class="text-sm font-normal text-base-content/60">({{ $ideas->total() }})</span>
        </h2>
        <div class="flex items-center gap-2">
            
            <a
                href="/ideas/create"
                class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
            >
                + New Idea
            </a>

            @if ($ideas->count() > 0)
                <form
                    method="POST"
                    action="/ideas"
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

 
    <x-idea.status-filter />

    <div class="pb-24 text-white">
        <ul class="mt-4 grid grid-cols-[repeat(auto-fit,minmax(250px,1fr))] gap-6">
            @forelse ($ideas as $idea)
                <x-idea.idea-card :idea="$idea" />
            @empty

            <x-idea.empty-state
                :filtered="request()->filled('state')"
                :state="request('state')"
            />

            @endforelse
        </ul>

        <div class="fixed inset-x-0 bottom-4 z-40 flex justify-center px-4">
            {{-- <x-idea.pagination :paginator="$ideas" /> --}}
            {{ $ideas->links() }}
        </div>
    </div>
</x-layout>
