@props([
    'value' => null,
])

<div {{ $attributes->only('class')->merge(['class' => 'space-y-2']) }}>
    <label for="email" class="block text-sm font-medium text-base-content/80">Email address</label>
    <input
        id="email"
        name="email"
        type="email"
        value="{{ old('email', $value) }}"
        autocomplete="email"
        required
        {{ $attributes->except('class') }}
        class="h-12 w-full rounded-xl border border-base-300 bg-base-100/80 px-4 text-sm text-base-content outline-none transition placeholder:text-base-content/35 focus:border-primary focus:ring-4 focus:ring-primary/15 @error('email') border-error focus:border-error focus:ring-error/15 @enderror"
        placeholder="you@example.com"
    >
    <x-error name="email" />
</div>
