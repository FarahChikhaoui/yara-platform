<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>YARA</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="bg-slate-100 text-slate-900">

<div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">

    {{-- Mobile overlay --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-30 bg-slate-950/60 lg:hidden"
        style="display: none;"
    ></div>

    {{-- Sidebar --}}
    <aside
        class="fixed inset-y-0 left-0 z-40 flex h-screen flex-shrink-0 flex-col bg-slate-950 text-white transition-all duration-200 lg:static"
        :class="[
            sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
            sidebarCollapsed ? 'lg:w-20' : 'lg:w-80',
            'w-80'
        ]"
    >

        {{-- Logo --}}
        <div class="flex items-center justify-between border-b border-slate-800 p-6">

            <a href="/admin/dashboard" class="flex min-w-0 items-center gap-4">

                <img
                    src="{{ asset('assets/yellomind_logo.png') }}"
                    class="h-12 w-12 flex-shrink-0 object-contain"
                    alt="Yellomind"
                >

                <div class="min-w-0" x-show="!sidebarCollapsed" x-transition.opacity>
                    <h1 class="text-lg font-bold">
                        YARA
                    </h1>

                    <p class="whitespace-nowrap text-xs text-slate-400">
                        Admin Management
                    </p>
                </div>

            </a>

            {{-- Collapse toggle (desktop only) --}}
            <button
                @click="sidebarCollapsed = !sidebarCollapsed"
                class="hidden h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-800 hover:text-white lg:flex"
                :class="sidebarCollapsed && 'rotate-180'"
                aria-label="Toggle sidebar"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
            </button>

            {{-- Mobile close --}}
            <button
                @click="sidebarOpen = false"
                class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white lg:hidden"
                aria-label="Close sidebar"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>

        </div>

        {{-- Navigation --}}
        <nav class="flex-1 space-y-6 overflow-y-auto p-4 text-sm font-medium">

            {{-- General --}}
            <div>
                <p
                    x-show="!sidebarCollapsed"
                    class="mb-2 px-4 text-xs font-semibold uppercase tracking-wide text-slate-500"
                >
                    General
                </p>

                <a
                    href="/admin/dashboard"
                    title="Overview"
                    class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition
                    {{ request()->is('admin/dashboard')
                        ? 'bg-yellow-400 text-slate-950'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                >
                    <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Overview</span>
                </a>
            </div>

            {{-- Assessment configuration --}}
            <div>
                <p
                    x-show="!sidebarCollapsed"
                    class="mb-2 px-4 text-xs font-semibold uppercase tracking-wide text-slate-500"
                >
                    Assessment Framework
                </p>

                <div class="space-y-1">

                    <a
                        href="/admin/dimensions"
                        title="Dimensions"
                        class="group flex items-center gap-3 rounded-xl px-4 py-3 transition
                        {{ request()->is('admin/dimensions*')
                            ? 'bg-yellow-400 text-slate-950'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                    >
                        <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/><path d="M12 3v9l6 3"/>
                        </svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity>Dimensions</span>
                    </a>

                    <a
                        href="/admin/questions"
                        title="Questions"
                        class="group flex items-center gap-3 rounded-xl px-4 py-3 transition
                        {{ request()->is('admin/questions*')
                            ? 'bg-yellow-400 text-slate-950'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                    >
                        <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9.5 9a2.5 2.5 0 1 1 3.4 2.3c-.9.4-1.4 1-1.4 2.2"/><path d="M12 17h.01"/><circle cx="12" cy="12" r="9"/>
                        </svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity>Questions</span>
                    </a>

                    <a
                        href="/admin/maturity-levels"
                        title="Maturity Levels"
                        class="group flex items-center gap-3 rounded-xl px-4 py-3 transition
                        {{ request()->is('admin/maturity-levels*')
                            ? 'bg-yellow-400 text-slate-950'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                    >
                        <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3v18h18"/><path d="M7 15l4-5 3 3 5-7"/>
                        </svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity>Maturity Levels</span>
                    </a>

                   <div class="mt-7 px-4">
    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">
        Benchmark Data
    </p>
</div>

<a
    href="{{ route('admin.country-data.index') }}"
    class="mt-2 flex items-center gap-3 rounded-xl px-4 py-3
        {{ request()->routeIs('admin.country-data.*')
            ? 'bg-yellow-400 text-slate-950'
            : 'text-slate-200 hover:bg-slate-800' }}"
>
    <svg
        class="h-5 w-5"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="1.8"
            d="M12 21a9 9 0 100-18 9 9 0 000 18zm0 0c2.2-2.4 3.5-5.5 3.5-9S14.2 5.4 12 3m0 18c-2.2-2.4-3.5-5.5-3.5-9S9.8 5.4 12 3M3.5 9h17M3.5 15h17"
        />
    </svg>

    <span>Country Data</span>
</a>

                </div>
            </div>

            {{-- Operations --}}
            <div>
                <p
                    x-show="!sidebarCollapsed"
                    class="mb-2 px-4 text-xs font-semibold uppercase tracking-wide text-slate-500"
                >
                    Operations
                </p>

                <a
                    href="/admin/assessments"
                    title="Assessments"
                    class="group flex items-center gap-3 rounded-xl px-4 py-3 transition
                    {{ request()->is('admin/assessments*')
                        ? 'bg-yellow-400 text-slate-950'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                >
                    <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>Assessments</span>
                </a>
                <a 
    href="{{ route('admin.transformations.index') }}"
    title="Transformation Requests" 
    class="group flex items-center gap-3 rounded-xl px-4 py-3 transition 
    {{ request()->is('admin/transformations*') 
        ? 'bg-yellow-400 text-slate-950' 
        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}" 
>
    <svg 
        class="h-5 w-5 flex-shrink-0" 
        viewBox="0 0 24 24" 
        fill="none" 
        stroke="currentColor" 
        stroke-width="2" 
        stroke-linecap="round" 
        stroke-linejoin="round"
    >
        <path d="M3 3v18h18"/>
        <path d="M7 16l4-4 3 3 5-7"/>
    </svg>

    <span x-show="!sidebarCollapsed" x-transition.opacity>
        Transformation Requests
    </span>
</a>

<a
    href="{{ route('admin.audit-logs.index') }}"
    title="Audit Logs"
    class="group flex items-center gap-3 rounded-xl px-4 py-3 transition
    {{ request()->routeIs('admin.audit-logs.*')
        ? 'bg-yellow-400 text-slate-950'
        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
>
    <svg
        class="h-5 w-5 flex-shrink-0"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M9 12l2 2 4-4"/>
        <path d="M21 12a9 9 0 1 1-9-9"/>
        <path d="M12 7v5"/>
    </svg>

    <span x-show="!sidebarCollapsed" x-transition.opacity>
        Audit Logs
    </span>
</a>

            </div>

        </nav>

       

    </aside>

    {{-- Main area --}}
    <main class="flex h-screen min-w-0 flex-1 flex-col overflow-hidden">

        {{-- Header --}}
        <header class="flex flex-shrink-0 items-center justify-between gap-4 border-b border-slate-200 bg-white px-4 py-4 shadow-sm sm:px-8 sm:py-5">

            <div class="flex min-w-0 items-center gap-4">

                {{-- Mobile menu button --}}
                <button
                    @click="sidebarOpen = true"
                    class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 lg:hidden"
                    aria-label="Open sidebar"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <div class="min-w-0">
                    <p class="text-sm text-slate-500">
                        Yellomind Consulting
                    </p>

                    <h2 class="truncate text-xl font-bold text-slate-950">
                        @yield('page-title', 'Admin Dashboard')
                    </h2>
                </div>

            </div>

            <div class="flex items-center gap-3">

                {{-- Search --}}
                <div class="relative hidden md:block">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input
                        type="search"
                        placeholder="Search..."
                        class="w-56 rounded-lg border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm placeholder:text-slate-400 focus:border-yellow-400 focus:bg-white focus:ring-yellow-400"
                    >
                </div>

               {{-- Notifications --}}
@php
    $adminUnreadNotifications = auth()->user()
        ->unreadNotifications()
        ->latest()
        ->take(5)
        ->get();

    $adminUnreadCount = auth()->user()
        ->unreadNotifications()
        ->count();
@endphp

<div
    class="relative"
    x-data="{ notificationsOpen: false }"
>
    {{-- Bell --}}
    <button
        type="button"
        @click="notificationsOpen = !notificationsOpen"
        class="relative flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100"
        aria-label="Notifications"
    >
        <svg
            class="h-5 w-5"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
        </svg>

        @if($adminUnreadCount > 0)
            <span
                class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-yellow-400 px-1 text-[10px] font-bold text-slate-950 ring-2 ring-white"
            >
                {{ $adminUnreadCount > 9 ? '9+' : $adminUnreadCount }}
            </span>
        @endif
    </button>


    {{-- Dropdown --}}
    <div
        x-show="notificationsOpen"
        x-cloak
        @click.outside="notificationsOpen = false"
        x-transition
        class="absolute right-0 z-50 mt-3 w-96 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl shadow-slate-900/10"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">

            <div>
                <p class="font-bold text-slate-950">
                    Notifications
                </p>

                @if($adminUnreadCount > 0)
                    <p class="mt-0.5 text-xs text-slate-400">
                        {{ $adminUnreadCount }}
                        {{ Str::plural('unread notification', $adminUnreadCount) }}
                    </p>
                @endif
            </div>

        </div>


        {{-- Items --}}
        <div class="max-h-96 overflow-y-auto">

            @forelse($adminUnreadNotifications as $notification)

                <a
                    href="{{ route('notifications.open', $notification->id) }}"
                    class="group block border-b border-slate-100 px-5 py-4 transition last:border-b-0 hover:bg-slate-50"
                >
                    <div class="flex gap-3">

                        <div class="mt-0.5 flex h-9 w-9 flex-none items-center justify-center rounded-xl bg-yellow-100 text-yellow-700">
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M3 3v18h18"/>
                                <path d="M7 16l4-4 3 3 5-7"/>
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">

                            <div class="flex items-start justify-between gap-3">

                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </p>

                                <span class="mt-1 h-2 w-2 flex-none rounded-full bg-yellow-400"></span>

                            </div>

                            <p class="mt-1 text-sm leading-5 text-slate-500">
                                {{ $notification->data['message'] ?? '' }}
                            </p>

                            @if(!empty($notification->data['company_name']))
                                <p class="mt-2 text-xs font-semibold text-slate-600">
                                    {{ $notification->data['company_name'] }}
                                </p>
                            @endif

                            <p class="mt-1 text-xs text-slate-400">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>

                        </div>

                    </div>
                </a>

            @empty

                <div class="px-6 py-10 text-center">

                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="M6 8a6 6 0 0112 0c0 7 3 9 3 9H3s3-2 3-9"/>
                        </svg>
                    </div>

                    <p class="mt-3 text-sm font-semibold text-slate-700">
                        You're all caught up
                    </p>

                    <p class="mt-1 text-xs text-slate-400">
                        New Transformation requests will appear here.
                    </p>

                </div>

            @endforelse

        </div>
    </div>
</div>

                {{-- Shared user dropdown --}}
                <x-user-dropdown button-text-class="text-slate-500" />

            </div>

        </header>

        {{-- Page content --}}
        <section
            class="flex flex-col overflow-y-auto p-4 sm:p-8"
        >
            @yield('content')
        </section>

    </main>

</div>

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