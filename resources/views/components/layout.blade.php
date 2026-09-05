@props([
    'title' => 'Default Title',
    'showNav' => true
])



<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />

    <style>
       .content-width {
            width: fit-content;
            max-width: 400px;
            margin: auto;
        }

    </style>

</head>
<body {{ $attributes }}>
    @if ($showNav)
        <x-nav-bar />
    @endif

    <main class="max-w-4xl mx-auto mt-1 px-5">
        {{ $slot }}
    </main> 
    <x-status-message />
</body>
</html>