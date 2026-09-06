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

        document.addEventListener('click', (event) => {
            const activeMenu = event.target.closest('[data-menu]');

            if (activeMenu) {
                closeOtherMenus(activeMenu);
            }
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

        const strengthLevels = [
            { label: 'Very weak', color: 'bg-error', text: 'text-error', hint: 'Add more characters to make it harder to guess.' },
            { label: 'Weak', color: 'bg-warning', text: 'text-warning', hint: 'Try adding uppercase letters, numbers, or symbols.' },
            { label: 'Medium', color: 'bg-info', text: 'text-info', hint: 'A few more characters can make this much stronger.' },
            { label: 'Strong', color: 'bg-success', text: 'text-success', hint: 'Nice choice. This password is difficult to guess.' },
        ];

        const getPasswordStrength = (password) => {
            let score = 0;

            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score++;
            if (/\d/.test(password) || /[^A-Za-z0-9]/.test(password)) score++;

            return Math.max(1, Math.min(score, 4));
        };

        document.querySelectorAll('[data-password-strength]').forEach((meter) => {
            const input = document.getElementById(meter.dataset.passwordStrength);
            const label = meter.querySelector('[data-strength-label]');
            const hint = meter.querySelector('[data-strength-hint]');
            const segments = meter.querySelectorAll('[data-strength-segment]');

            input?.addEventListener('input', () => {
                const password = input.value;

                if (!password) {
                    meter.classList.add('hidden');
                    return;
                }

                const strength = getPasswordStrength(password);
                const level = strengthLevels[strength - 1];

                meter.classList.remove('hidden');
                label.textContent = level.label;
                hint.textContent = level.hint;
                label.className = `font-semibold ${level.text}`;

                segments.forEach((segment, index) => {
                    segment.className = `h-1.5 flex-1 rounded-full transition-colors duration-300 ${index < strength ? level.color : 'bg-base-300'}`;
                });
            });
        });
    </script>
</body>
</html>
