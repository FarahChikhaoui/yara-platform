@props([
    'buttonTextClass' => 'text-slate-500',
])

<div class="relative" data-user-dropdown>

    <button
        type="button"
        data-user-dropdown-button
        class="flex items-center gap-2 transition hover:opacity-90"
        aria-expanded="false"
    >
        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-yellow-400 font-bold text-slate-950">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>

        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="{{ $buttonTextClass }}"
        >
            <path d="m6 9 6 6 6-6"/>
        </svg>
    </button>

    <div
        data-user-dropdown-menu
        class="absolute right-0 z-50 mt-3 hidden w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white text-slate-900 shadow-xl"
    >
        <div class="border-b border-slate-100 px-4 py-3">
            <p class="font-semibold text-slate-900">
                {{ Auth::user()->name }}
            </p>

            <p class="mt-1 truncate text-xs text-slate-500">
                {{ Auth::user()->email }}
            </p>
        </div>

        <a
            href="{{ route('profile.edit') }}"
            class="block px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
        >
            Profile
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="w-full px-4 py-3 text-left text-sm font-medium text-red-600 transition hover:bg-red-50"
            >
                Logout
            </button>
        </form>
    </div>

</div>