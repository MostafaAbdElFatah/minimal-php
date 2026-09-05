<x-layout title="Ideas" class="bg-gray-700 p-5 max-w-4xl mx-auto" :showNav="false">
    <form method="POST" action="/ideas">
        @csrf
        <div>
            <h2 class="text-base/7 font-semibold text-white">New Idea</h2>
            <p class="mt-1 text-sm/6 text-gray-400">
                This information will be displayed publicly so be careful what you share.
            </p>

            {{-- <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                <div class="sm:col-span-4">
                <label for="title" class="block text-sm/6 font-medium text-white">Title</label>
                <div class="mt-2">
                    <div class="flex items-center rounded-md bg-white/5 pl-3 outline-1 -outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                    <input id="title" type="text" name="title" placeholder="title" class="block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-white placeholder:text-gray-500 focus:outline-none sm:text-sm/6" />
                    </div>
                </div>
            </div> --}}

            <div class="col-span-full">
                <label for="description" class="block text-sm/6 font-medium text-white">Description</label>
                <div class="mt-2">
                    <textarea id="description" name="description" rows="3" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"></textarea>
                </div>
                    <p class="mt-3 text-sm/6 text-gray-400">Write a few sentences about your idea.</p>
                </div>
            </div>
            </div>   
            <div class="border-b border-white/10 pb-4">       
        </div>
        
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" onclick="history.back()" class="text-sm/6 font-semibold text-white">Cancel</button>
            <button type="submit" class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Save</button>
        </div>

        @if (count($ideas))
            <div class="mt-10 text-white">
            <h2 class = "font-bold text-lg">Your Ideas</h2>
            <ul class="mt-4 list-disc list-inside text-sm/6 text-gray-400">
                @foreach ($ideas as $idea)
                    <li><a href="" class="hover:text-indigo-400">{{ $idea }}</a></li>
                @endforeach
            </ul>
        </div>
        @endif
        
    </form>
</x-layout>