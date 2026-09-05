<x-layout title="Ideas" class="bg-gray-700 p-5 max-w-4xl mx-auto" :showNav="false">
    <form method="POST" action="/ideas/create">
        @csrf

        <div>
            <h2 class="text-base/7 font-semibold text-white">New Idea</h2>

            <p class="mt-1 text-sm/6 text-gray-400">
                This information will be displayed publicly so be careful what you share.
            </p>

            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                {{-- Title --}}
                <div class="sm:col-span-4">
                    <label for="title" class="block text-sm/6 font-medium text-white">
                        Title
                    </label>

                    <div class="mt-2">
                        <div class="flex items-center rounded-md bg-white/5 pl-3 outline-1 -outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                            <input
                                id="title"
                                type="text"
                                name="title"
                                value="{{ old('title') }}"
                                placeholder="Title"
                                required
                                class="block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-white placeholder:text-gray-500 focus:outline-none sm:text-sm/6"
                            />
                        </div>
                    </div>

                    <x-error name="title"/>                
                </div>

                {{-- State --}}
                <div class="sm:col-span-4">
                    <label for="state" class="block text-sm/6 font-medium text-white">
                        State
                    </label>

                    <div class="mt-2">
                        <select
                            id="state"
                            name="state"
                            required
                            class="block w-full rounded-md bg-white/5 px-3 py-2 text-base text-white outline-1 -outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                        >
                            @foreach (\App\Enums\IdeaState::cases() as $state)
                                <option
                                    value="{{ $state->value }}"
                                    class="bg-gray-800"
                                    {{ old('state', 'pending') === $state->value ? 'selected' : '' }}
                                >
                                    {{ $state->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <x-error name="state" />

                </div>

                {{-- Description --}}
                <div class="col-span-full">
                    <label for="description" class="block text-sm/6 font-medium text-white">
                        Description
                    </label>

                    <div class="mt-2">
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            required
                            class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                            placeholder="Describe your idea..."
                        >{{ old('description') }}</textarea>
                    </div>

                    <p class="mt-3 text-sm/6 text-gray-400">
                        Write a few sentences about your idea.
                    </p>

                    <x-error name="description"/> 
                </div>
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