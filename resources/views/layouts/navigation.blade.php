<nav x-data="{ open: false }" class="bg-white">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="flex h-16 justify-between">

            <div class="flex">

                <!-- Logo -->
                <div class="flex shrink-0 items-center">
                    <a href="{{ auth()->check() ? route('dashboard') : url('/') }}">
                        <x-application-logo
                            class="block h-9 w-auto fill-current text-gray-800"
                        />
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">

                    @auth
                        <x-nav-link
                            :href="route('dashboard')"
                            :active="request()->routeIs('dashboard')"
                        >
                            {{ __('Dashboard') }}
                        </x-nav-link>
                   @else
    <a
        href="{{ url('/') }}"
        class="inline-flex items-center px-1 pt-1 text-sm font-medium text-slate-600 transition hover:text-slate-950"
    >
        Home
    </a>

    <a
        href="{{ route('pulse.intro') }}"
        class="inline-flex items-center px-1 pt-1 text-sm font-medium text-slate-600 transition hover:text-slate-950"
    >
        Pulse Check
    </a>
@endauth

                </div>

            </div>

            <!-- Desktop Right Side -->
            <div class="hidden items-center sm:flex sm:ml-6">

                @auth

                    <!-- Authenticated User Dropdown -->
                    <x-dropdown align="right" width="48">

                        <x-slot name="trigger">

                            <button
                                type="button"
                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                            >
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ml-1">
                                    <svg
                                        class="h-4 w-4 fill-current"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </button>

                        </x-slot>

                        <x-slot name="content">

                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link
                                    :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                >
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>

                        </x-slot>

                    </x-dropdown>

                @else

                    <!-- Guest Links -->
                    <div class="flex items-center gap-3">

                        <a
                            href="{{ route('login') }}"
                            class="text-sm font-semibold text-gray-600 transition hover:text-gray-900"
                        >
                            Log in
                        </a>

                        <a
                            href="{{ route('register') }}"
                            class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                        >
                            Create account
                        </a>

                    </div>

                @endauth

            </div>

            <!-- Mobile Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">

                <button
                    type="button"
                    @click="open = !open"
                    class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                >
                    <svg
                        class="h-6 w-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <path
                            :class="{ 'hidden': open, 'inline-flex': !open }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />

                        <path
                            :class="{ 'hidden': !open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>

            </div>

        </div>

    </div>

    <!-- Responsive Navigation Menu -->
    <div
        :class="{ 'block': open, 'hidden': !open }"
        class="hidden sm:hidden"
    >

        <div class="space-y-1 pb-3 pt-2">

            @auth

                <x-responsive-nav-link
                    :href="route('dashboard')"
                    :active="request()->routeIs('dashboard')"
                >
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

            @else

                <x-responsive-nav-link
                    :href="url('/')"
                    :active="request()->is('/')"
                >
                    {{ __('Home') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link
                    :href="route('pulse.intro')"
                    :active="request()->routeIs('pulse.*')"
                >
                    {{ __('Pulse Check') }}
                </x-responsive-nav-link>

            @endauth

        </div>

        @auth

            <!-- Authenticated Mobile Options -->
            <div class="border-t border-gray-200 pb-1 pt-4">

                <div class="px-4">
                    <div class="text-base font-medium text-gray-800">
                        {{ Auth::user()->name }}
                    </div>

                    <div class="text-sm font-medium text-gray-500">
                        {{ Auth::user()->email }}
                    </div>
                </div>

                <div class="mt-3 space-y-1">

                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link
                            :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                        >
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>

                </div>

            </div>

        @else

            <!-- Guest Mobile Options -->
            <div class="border-t border-gray-200 pb-4 pt-4">

                <div class="space-y-1">

                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Create account') }}
                    </x-responsive-nav-link>

                </div>

            </div>

        @endauth

    </div>

</nav>