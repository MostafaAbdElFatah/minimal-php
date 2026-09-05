@props([
    'name' => 'name',
    'label' => 'Name',
    'value' => null,
    'autocomplete' => 'name',
])

<div {{ $attributes->only('class')->merge(['class' => 'space-y-2']) }}>
    <label for="{{ $name }}" class="block text-sm font-medium text-base-content/80">
        {{ $label }}
    </label>
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="text"
        value="{{ old($name, $value) }}"
        autocomplete="{{ $autocomplete }}"
        required
        {{ $attributes->except('class') }}
        class="h-12 w-full rounded-xl border border-base-300 bg-base-100/80 px-4 text-sm text-base-content outline-none transition placeholder:text-base-content/35 focus:border-primary focus:ring-4 focus:ring-primary/15 @error($name) border-error focus:border-error focus:ring-error/15 @enderror"
        placeholder="{{ $label }}"
    >
    <x-error :name="$name" />
</div>
