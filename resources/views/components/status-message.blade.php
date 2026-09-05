@if (session('status'))
    <div
        id="status-message"
        {{ $attributes->merge(['class' => 'fixed bottom-5 right-5 z-50 rounded-lg bg-green-600 px-5 py-3 text-white shadow-lg']) }}
    >
        {{ session('status') }}
    </div>

    <script>
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                document.getElementById('status-message')?.remove();
            }
        });
    </script>
@endif
