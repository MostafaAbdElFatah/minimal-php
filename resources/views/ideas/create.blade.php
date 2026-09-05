<x-layout title="Ideas">
    <form method="POST" action="/ideas/create">
        @csrf

        <div>
            <h2 class="text-base/7 font-semibold text-white">New Idea</h2>

            <p class="mt-1 text-sm/6 text-gray-400">
                This information will be displayed publicly so be careful what you share.
            </p>

            <div class="mt-6 list-">

                {{-- Title --}}
                <div>
                    <label
                        for="title"
                        class="text-xs font-semibold uppercase tracking-wider text-gray-500"
                    >
                        Title
                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.03] px-4 py-2.5 text-sm text-white placeholder-gray-500 outline-none transition-all duration-200 focus:border-indigo-500/50 focus:bg-white/[0.05] focus:ring-2 focus:ring-indigo-500/20"
                        placeholder="Enter idea title"
                    >
                    <x-error name="title"/> 
                </div>
             

                {{-- State --}}
                <div>
                    <label
                        for="state"
                        class="text-xs font-semibold uppercase tracking-wider text-gray-500"
                    >
                        State
                    </label>

                    <select
                        id="state"
                        name="state"
                        required
                        class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.03] px-4 py-2.5 text-sm text-white outline-none transition-all duration-200 focus:border-indigo-500/50 focus:bg-white/[0.05] focus:ring-2 focus:ring-indigo-500/20"
                    >
                        @foreach (\App\Enums\IdeaState::cases() as $state)
                            <option
                                value="{{ $state->value }}"
                                class="bg-gray-800"
                                {{ old('state') === $state->value ? 'selected' : '' }}
                            >
                                {{ $state }}
                            </option>
                        @endforeach
                    </select>
                    <x-error name="state"/> 
                </div>

                {{-- Description --}}
                <div>
                    <label
                        for="description"
                        class="text-xs font-semibold uppercase tracking-wider text-gray-500"
                    >
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        required
                        class="mt-2 w-full resize-none rounded-xl border border-white/10 bg-white/[0.03] px-4 py-2.5 text-sm leading-6 text-gray-300 placeholder-gray-500 outline-none transition-all duration-200 focus:border-indigo-500/50 focus:bg-white/[0.05] focus:ring-2 focus:ring-indigo-500/20"
                        placeholder="Describe your idea..."
                    >{{ old('description') }}</textarea>

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