@props([
    'value' => null,
])

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
        class="mt-2 w-full resize-none rounded-xl border @error('description') border-error @else border-white/10 @enderror bg-white/[0.03] px-4 py-2.5 text-sm leading-6 text-gray-300 placeholder-gray-500 outline-none transition-all duration-200 focus:border-indigo-500/50 focus:bg-white/[0.05] focus:ring-2 focus:ring-indigo-500/20"
        placeholder="Describe your idea..."
    >{{ old('description', $value) }}</textarea>

    <x-error name="description" />
</div>
