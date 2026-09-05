<div class="mt-2">
    <form method="GET" action="/">
        <label
            for="state"
            class="text-xs font-semibold uppercase tracking-wider text-gray-500"
        >
            Filter by state
        </label>

    <div class="mt-2 flex items-center gap-2">
        <select
            id="state"
            name="state"
            onchange="this.form.submit()"
            class="select select-sm w-40 border-primary/30 bg-primary/5 text-sm font-normal text-base-content focus:border-primary focus:outline-none focus:ring-0"
            >
            <option value="">
                All states
            </option>

            @foreach (\App\Enums\IdeaState::cases() as $state)
                <option
                    value="{{ $state->value }}"
                    @selected(request('state') === $state->value)
                >
                    {{ $state }}
                </option>
            @endforeach
        </select>

        @if (request('state'))
            <a
                href="/"
                class="btn btn-sm btn-ghost text-base-content/60 transition hover:bg-base-200 hover:text-primary"
            >
                Clear
            </a>
        @endif
    </div>
    </form>
</div>