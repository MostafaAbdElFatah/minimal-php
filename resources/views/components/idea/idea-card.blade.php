<a
    href="{{ url('/ideas/' . $idea->id) }}"
    class="card w-full bg-{{  $idea->color }}-500/10 text-primary-content"
>
    <div class="card-body">
        <h2 class="card-title">
            {{ $idea->title }}
        </h2>

        <p class="text-xs">
            {{ $idea->description }}
        </p>
    </div>
</a>