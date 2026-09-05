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

    <script>
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.dataset.theme = savedTheme;
    </script>

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

    <script>
        const menus = document.querySelectorAll('[data-menu]');

        const closeMenu = (menu) => {
            if (menu.matches('details')) {
                menu.removeAttribute('open');
            }

            menu.querySelector('[role="button"]')?.blur();
        };

        const closeOtherMenus = (activeMenu) => {
            menus.forEach((menu) => {
                if (menu !== activeMenu) {
                    closeMenu(menu);
                }
            });
        };

        menus.forEach((menu) => {
            menu.querySelector(':scope > summary, :scope > [role="button"]')?.addEventListener('click', () => {
                closeOtherMenus(menu);
            });
        });

        const activeTheme = localStorage.getItem('theme') || 'dark';

        document.querySelectorAll('.theme-controller').forEach((controller) => {
            controller.checked = controller.value === activeTheme;

            controller.addEventListener('change', () => {
                document.documentElement.dataset.theme = controller.value;
                localStorage.setItem('theme', controller.value);
                controller.closest('details')?.removeAttribute('open');
            });
        });

        document.addEventListener('click', (event) => {
            const menuItem = event.target.closest('details a, details button, .dropdown a, .dropdown button');

            if (!menuItem) {
                return;
            }

            menuItem.closest('details')?.removeAttribute('open');
            menuItem.closest('.dropdown')?.querySelector('[role="button"]')?.blur();
        });

        document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
            toggle.addEventListener('click', () => {
                const input = document.getElementById(toggle.dataset.passwordToggle);
                const isVisible = input.type === 'text';

                input.type = isVisible ? 'password' : 'text';
                toggle.setAttribute('aria-pressed', String(!isVisible));
                toggle.setAttribute('aria-label', `${isVisible ? 'Show' : 'Hide'} password`);
                toggle.querySelector('[data-password-eye="hidden"]')?.classList.toggle('hidden', !isVisible);
                toggle.querySelector('[data-password-eye="shown"]')?.classList.toggle('hidden', isVisible);
            });
        });
    </script>
</body>
</html>
