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

    <div class="flex items-center gap-3">

        {{-- Consultant notifications --}}
        @if(auth()->user()->isConsultant())

            @php
                $unreadNotifications = auth()->user()
                    ->unreadNotifications()
                    ->latest()
                    ->take(5)
                    ->get();

                $unreadNotificationCount = auth()->user()
                    ->unreadNotifications()
                    ->count();
            @endphp

            <div class="relative" data-notification-dropdown>

                {{-- Bell --}}
                <button
                    type="button"
                    data-notification-button
                    aria-expanded="false"
                    aria-label="Notifications"
                    class="relative flex h-10 w-10 items-center justify-center rounded-xl text-slate-300 transition hover:bg-white/10 hover:text-white"
                >
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 00-12 0v.75a8.967 8.967 0 01-2.312 6.022 23.848 23.848 0 005.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
                        />
                    </svg>

                    @if($unreadNotificationCount > 0)
                        <span
                            class="absolute -right-0.5 -top-0.5 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-yellow-400 px-1 text-[10px] font-bold text-slate-950 ring-2 ring-slate-950"
                        >
                            {{ $unreadNotificationCount > 9 ? '9+' : $unreadNotificationCount }}
                        </span>
                    @endif

                </button>

                {{-- Dropdown --}}
                <div
                    data-notification-menu
                    class="absolute right-0 z-50 mt-3 hidden w-96 overflow-hidden rounded-2xl border border-slate-200 bg-white text-slate-900 shadow-xl"
                >

                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">

                        <div>
                            <p class="font-bold text-slate-950">
                                Notifications
                            </p>

                            <p class="mt-0.5 text-xs text-slate-400">
                                {{ $unreadNotificationCount }}
                                unread
                            </p>
                        </div>

                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-50 text-yellow-600">
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 00-12 0v.75a8.967 8.967 0 01-2.312 6.022 23.848 23.848 0 005.455 1.31"
                                />
                            </svg>
                        </span>

                    </div>

                    @forelse($unreadNotifications as $notification)

                        <a
                            href="{{ route('notifications.open', $notification->id) }}"
                            class="block border-b border-slate-100 px-5 py-4 transition last:border-0 hover:bg-slate-50"
                        >

                            <div class="flex gap-3">

                                <span class="mt-1 h-2 w-2 flex-none rounded-full bg-yellow-400"></span>

                                <div class="min-w-0">

                                    <p class="text-sm font-bold text-slate-900">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </p>

                                    <p class="mt-1 text-sm leading-5 text-slate-500">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>

                                    @if(!empty($notification->data['company_name']))
                                        <p class="mt-2 text-xs font-semibold text-slate-700">
                                            {{ $notification->data['company_name'] }}
                                        </p>
                                    @endif

                                    <p class="mt-2 text-[11px] text-slate-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>

                                </div>

                            </div>

                        </a>

                    @empty

                        <div class="px-6 py-10 text-center">

                            <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4.5 12.75l6 6 9-13.5"
                                    />
                                </svg>
                            </div>

                            <p class="mt-3 text-sm font-semibold text-slate-700">
                                You're all caught up
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                No unread notifications.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        @endif

        {{-- User menu --}}
        <x-user-dropdown button-text-class="text-slate-300" />

    </div>

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
const notificationDropdown =
    document.querySelector('[data-notification-dropdown]');

if (notificationDropdown) {

    const notificationButton =
        notificationDropdown.querySelector('[data-notification-button]');

    const notificationMenu =
        notificationDropdown.querySelector('[data-notification-menu]');

    notificationButton?.addEventListener('click', function (event) {

        event.stopPropagation();

        const shouldOpen =
            notificationMenu.classList.contains('hidden');

        notificationMenu.classList.toggle('hidden', !shouldOpen);

        notificationButton.setAttribute(
            'aria-expanded',
            shouldOpen ? 'true' : 'false'
        );
    });

    notificationMenu?.addEventListener('click', function (event) {
        event.stopPropagation();
    });

    document.addEventListener('click', function () {
        notificationMenu?.classList.add('hidden');

        notificationButton?.setAttribute(
            'aria-expanded',
            'false'
        );
    });

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {
            notificationMenu?.classList.add('hidden');

            notificationButton?.setAttribute(
                'aria-expanded',
                'false'
            );
        }
    });
}
</script>

</body>

</html>