@props([
    'title' => 'Default Title',
    'showNav' => true
])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>{{ $title }}</title>
    <style>
        nav {
            display: flex;
            gap: 1rem;
            background-color: #f0f0f0;
            padding: 1rem;
        }
        nav a {
            text-decoration: none;
            color: blue;
        }

        nav a:hover {
            text-decoration: underline;
        }

       .content-width {
            width: fit-content;
            max-width: 400px;
            margin: auto;
        }

    </style>

</head>
<body {{ $attributes }}>
    @if ($showNav)
        <nav>
            <a href="/"> Home </a> 
            <a href="/about"> About us </a> 
            <a href="/contact"> Contact us </a>
        </nav>
    @endif

    <main>
        {{ $slot }}
    </main> 

</body>
</html>