@props([
    'value' => null,
])

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
        value="{{ old('title', $value) }}"
        required
        class="mt-2 w-full rounded-xl border @error('title') border-error @enderror border-white/10 bg-white/[0.03] px-4 py-2.5 text-sm text-white placeholder-gray-500 outline-none transition-all duration-200 focus:border-indigo-500/50 focus:bg-white/[0.05] focus:ring-2 focus:ring-indigo-500/20"
        placeholder="Enter idea title"
    >
    <x-error name="title" />
</div>
