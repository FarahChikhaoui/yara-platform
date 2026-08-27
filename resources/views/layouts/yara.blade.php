<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>YARA Platform</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">

<header class="border-b-4 border-yellow-400 bg-slate-950 text-white">

    <div class="mx-auto flex max-w-7xl items-center justify-between px-8 py-5">

        {{-- Logo --}}
        <div class="flex items-center gap-3">

            <img
                src="{{ asset('assets/yellomind_logo.png') }}"
                alt="Yellomind Logo"
                class="h-10 w-10 object-contain"
            >

            <div>
                <h1 class="text-xl font-bold tracking-wide">
                    YARA
                </h1>

                <p class="text-sm text-slate-300">
                    Yellomind · AI Readiness Assessment
                </p>
            </div>

        </div>

        {{-- Authentication actions --}}
@auth
    {{-- Logged-in user --}}
    <x-user-dropdown button-text-class="text-slate-300" />
@else
    {{-- Guest / Pulse Check visitor --}}
    <div class="flex items-center gap-4">

        <a
            href="{{ route('login') }}"
            class="text-sm font-semibold text-slate-300 transition hover:text-white"
        >
            Log in
        </a>

        <a
            href="{{ route('register') }}"
            class="rounded-xl bg-yellow-400 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-yellow-300"
        >
            Create account
        </a>

    </div>
@endauth

    </div>

</header>

<main class="mx-auto max-w-7xl px-8 py-10">
    @yield('content')
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdowns = document.querySelectorAll('[data-user-dropdown]');

    const closeAllDropdowns = () => {
        dropdowns.forEach(function (dropdown) {
            const menu = dropdown.querySelector('[data-user-dropdown-menu]');
            const button = dropdown.querySelector('[data-user-dropdown-button]');

            menu?.classList.add('hidden');
            button?.setAttribute('aria-expanded', 'false');
        });
    };

    dropdowns.forEach(function (dropdown) {
        const button = dropdown.querySelector('[data-user-dropdown-button]');
        const menu = dropdown.querySelector('[data-user-dropdown-menu]');

        if (!button || !menu) {
            return;
        }

        button.addEventListener('click', function (event) {
            event.stopPropagation();

            const shouldOpen = menu.classList.contains('hidden');

            closeAllDropdowns();

            if (shouldOpen) {
                menu.classList.remove('hidden');
                button.setAttribute('aria-expanded', 'true');
            }
        });

        menu.addEventListener('click', function (event) {
            event.stopPropagation();
        });
    });

    document.addEventListener('click', function () {
        closeAllDropdowns();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeAllDropdowns();
        }
    });
});
</script>

</body>

</html>