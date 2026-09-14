@props([
    'buttonTextClass' => 'text-slate-500',
])

<div class="relative" data-user-dropdown>

    {{-- Trigger --}}
    <button
        type="button"
        data-user-dropdown-button
        class="group flex items-center gap-2 rounded-xl p-1 transition
               hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-yellow-400/50"
        aria-expanded="false"
        aria-label="Open account menu"
    >

        {{-- Avatar --}}
        <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden
                    rounded-full bg-yellow-400 font-bold text-slate-950
                    ring-2 ring-white/10 transition group-hover:ring-white/20">

            @if(Auth::user()->profile_photo)

                <img
                    src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                    alt="{{ Auth::user()->name }}"
                    class="h-full w-full object-cover"
                >

            @else

                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

            @endif

        </div>

        {{-- Chevron --}}
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="h-4 w-4 {{ $buttonTextClass }} transition-transform duration-200
                   group-aria-expanded:rotate-180"
        >
            <path d="m6 9 6 6 6-6"/>
        </svg>

    </button>


    {{-- Dropdown --}}
    <div
        data-user-dropdown-menu
        class="absolute right-0 z-50 mt-3 hidden w-72 overflow-hidden
               rounded-2xl border border-slate-200 bg-white
               text-slate-900 shadow-xl"
    >

        {{-- User identity --}}
        <div class="border-b border-slate-100 bg-slate-50/70 px-5 py-4">

            <div class="flex items-center gap-3">

                {{-- Small avatar --}}
                <div class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden
                            rounded-full bg-yellow-400 font-bold text-slate-950">

                    @if(Auth::user()->profile_photo)

                        <img
                            src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                            alt="{{ Auth::user()->name }}"
                            class="h-full w-full object-cover"
                        >

                    @else

                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                    @endif

                </div>

                <div class="min-w-0">

                    <p class="truncate text-sm font-bold text-slate-950">
                        {{ Auth::user()->name }}
                    </p>

                    <p class="mt-0.5 truncate text-xs text-slate-500">
                        {{ Auth::user()->email }}
                    </p>

                </div>

            </div>

        </div>


        {{-- Navigation --}}
        <div class="p-2">

            <a
                href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5
                       text-sm font-semibold text-slate-700 transition
                       hover:bg-slate-100 hover:text-slate-950"
            >

                <span class="flex h-8 w-8 items-center justify-center
                             rounded-lg bg-slate-100 text-slate-500">

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
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.118a7.5 7.5 0 0115 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.5-1.632z"
                        />
                    </svg>

                </span>

                <div>
                    <p>Account settings</p>
                    <p class="mt-0.5 text-xs font-normal text-slate-400">
                        Profile and security
                    </p>
                </div>

            </a>

        </div>


        {{-- Logout --}}
        <div class="border-t border-slate-100 p-2">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5
                           text-left text-sm font-semibold text-red-600
                           transition hover:bg-red-50"
                >

                    <span class="flex h-8 w-8 items-center justify-center
                                 rounded-lg bg-red-50 text-red-500">

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
                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3-6l3 3m0 0l-3 3m3-3H9"
                            />
                        </svg>

                    </span>

                    Log out

                </button>

            </form>

        </div>

    </div>

</div>