@props([
    'value' => null,
])

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
        class="mt-2 w-full rounded-xl border @error('state') border-error @else border-white/10 @enderror bg-white/[0.03] px-4 py-2.5 text-sm text-white outline-none transition-all duration-200 focus:border-indigo-500/50 focus:bg-white/[0.05] focus:ring-2 focus:ring-indigo-500/20"
    >
        @foreach (\App\Enums\IdeaState::cases() as $state)
            <option
                value="{{ $state->value }}"
                class="bg-gray-800"
                {{ old('state', $value) === $state->value ? 'selected' : '' }}
            >
                {{ $state }}
            </option>
        @endforeach
    </select>
    <x-error name="state" />
</div>
