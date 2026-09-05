<x-layout title="Home">
    <h1>Welcome</h1>
    <x-card 
        class="content-width"
        header="Welcome" 
        textAlign="left" 
        bgColor="#f9f9f9" 
        textColor="#333">
        {{ $greeting }}{{ $name }}, This is the welcome page.
    </x-card>

    @dump($tasks)

    @if (count($tasks))
        <p>Here are your tasks:</p>
         @foreach ($tasks as $task)
             <li>{{ $task }}</li>
        @endforeach
    @endif

    @unless (count($tasks))
        <p>There are no active tasks</p>
    @endunless

    <p>======================================</p>
    
    @forelse ($tasks as $task)
        <li>{{ $task }}</li>
    @empty
            <p>There are no active tasks</p>
    @endforelse

</x-layout>