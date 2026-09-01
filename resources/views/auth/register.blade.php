<x-guest-layout>

    <div class="w-full">

        {{-- Header --}}
        <div class="mb-7">
            <h1 class="text-3xl font-bold tracking-tight text-slate-950">
                Create your account
            </h1>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Start assessing your organization's AI readiness with YARA.
            </p>
        </div>


        {{-- Google --}}
        <a
            href="{{ route('auth.redirect', 'google') }}"
            class="flex w-full items-center justify-center gap-3 rounded-xl
                   border border-slate-200 bg-white px-4 py-3
                   text-sm font-semibold text-slate-700 shadow-sm
                   transition hover:border-slate-300 hover:bg-slate-50
                   focus:outline-none focus:ring-4 focus:ring-slate-100"
        >
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.99.66-2.25 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.85A10.99 10.99 0 0 0 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.1a6.6 6.6 0 0 1 0-4.2V7.05H2.18a11 11 0 0 0 0 9.9l3.66-2.85z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.05l3.66 2.85C6.71 7.31 9.14 5.38 12 5.38z"/>
            </svg>

            Continue with Google
        </a>


        {{-- Divider --}}
        <div class="my-6 flex items-center gap-3">
            <div class="h-px flex-1 bg-slate-200"></div>

            <span class="whitespace-nowrap text-xs font-medium text-slate-400">
                or continue with email
            </span>

            <div class="h-px flex-1 bg-slate-200"></div>
        </div>


        {{-- Registration Form --}}
        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf


            {{-- First + Last name --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div>
                    <label
                        for="first_name"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        First name
                    </label>

                    <input
                        id="first_name"
                        type="text"
                        name="first_name"
                        value="{{ old('first_name') }}"
                        required
                        autofocus
                        autocomplete="given-name"
                        placeholder="First name"
                        class="block w-full rounded-xl border border-slate-200 bg-white
                               px-4 py-3 text-sm text-slate-900
                               placeholder:text-slate-400 shadow-sm outline-none transition
                               focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                    >

                    <x-input-error
                        :messages="$errors->get('first_name')"
                        class="mt-2"
                    />
                </div>


                <div>
                    <label
                        for="last_name"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Last name
                    </label>

                    <input
                        id="last_name"
                        type="text"
                        name="last_name"
                        value="{{ old('last_name') }}"
                        required
                        autocomplete="family-name"
                        placeholder="Last name"
                        class="block w-full rounded-xl border border-slate-200 bg-white
                               px-4 py-3 text-sm text-slate-900
                               placeholder:text-slate-400 shadow-sm outline-none transition
                               focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                    >

                    <x-input-error
                        :messages="$errors->get('last_name')"
                        class="mt-2"
                    />
                </div>

            </div>


            {{-- Email --}}
            <div>
                <label
                    for="email"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Email address
                </label>

                <div class="relative">
                    <svg
                        class="pointer-events-none absolute left-4 top-1/2 h-5 w-5
                               -translate-y-1/2 text-slate-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
                        />
                    </svg>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="you@company.com"
                        class="block w-full rounded-xl border border-slate-200 bg-white
                               py-3 pl-11 pr-4 text-sm text-slate-900
                               placeholder:text-slate-400 shadow-sm outline-none transition
                               focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                    >
                </div>

                <x-input-error
                    :messages="$errors->get('email')"
                    class="mt-2"
                />
            </div>


            {{-- Password --}}
            <div>
                <label
                    for="password"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Password
                </label>

                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Create a password"
                        class="block w-full rounded-xl border border-slate-200 bg-white
                               px-4 py-3 pr-11 text-sm text-slate-900
                               placeholder:text-slate-400 shadow-sm outline-none transition
                               focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                    >

                    <button
                        type="button"
                        data-password-toggle="password"
                        aria-label="Show password"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 rounded-md
                               p-1 text-slate-400 transition hover:text-slate-700"
                    >
                        <svg class="eye-open h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>

                        <svg class="eye-closed hidden h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 4.6A10.6 10.6 0 0112 4.4c4.8 0 8.9 3 10.3 7.6a10.8 10.8 0 01-3 4.6M6.6 6.6A10.8 10.8 0 001.7 12c1.4 4.6 5.5 7.6 10.3 7.6a10.6 10.6 0 004.2-.9"/>
                        </svg>
                    </button>
                </div>

                <x-input-error
                    :messages="$errors->get('password')"
                    class="mt-2"
                />
            </div>


            {{-- Confirm password --}}
            <div>
                <label
                    for="password_confirmation"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Confirm password
                </label>

                <div class="relative">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Repeat your password"
                        class="block w-full rounded-xl border border-slate-200 bg-white
                               px-4 py-3 pr-11 text-sm text-slate-900
                               placeholder:text-slate-400 shadow-sm outline-none transition
                               focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                    >

                    <button
                        type="button"
                        data-password-toggle="password_confirmation"
                        aria-label="Show password"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 rounded-md
                               p-1 text-slate-400 transition hover:text-slate-700"
                    >
                        <svg class="eye-open h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>

                        <svg class="eye-closed hidden h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 4.6A10.6 10.6 0 0112 4.4c4.8 0 8.9 3 10.3 7.6a10.8 10.8 0 01-3 4.6M6.6 6.6A10.8 10.8 0 001.7 12c1.4 4.6 5.5 7.6 10.3 7.6a10.6 10.6 0 004.2-.9"/>
                        </svg>
                    </button>
                </div>

                <x-input-error
                    :messages="$errors->get('password_confirmation')"
                    class="mt-2"
                />
            </div>


            {{-- Create account --}}
            <button
                type="submit"
                class="group flex w-full items-center justify-center gap-2 rounded-xl
                       bg-slate-950 px-5 py-3.5 text-sm font-semibold text-white
                       shadow-sm transition hover:bg-slate-800
                       focus:outline-none focus:ring-4 focus:ring-slate-200"
            >
                Create account

                <svg
                    class="h-4 w-4 transition-transform group-hover:translate-x-0.5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

        </form>


        {{-- Login --}}
        <div class="mt-7 border-t border-slate-100 pt-6 text-center">
            <p class="text-sm text-slate-500">
                Already have an account?

                <a
                    href="{{ route('login') }}"
                    class="ml-1 font-semibold text-slate-950 transition hover:text-yellow-600"
                >
                    Sign in
                </a>
            </p>
        </div>

    </div>


    {{-- Password visibility --}}
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(button.dataset.passwordToggle);
                const openEye = button.querySelector('.eye-open');
                const closedEye = button.querySelector('.eye-closed');

                const hidden = input.type === 'password';

                input.type = hidden ? 'text' : 'password';

                openEye.classList.toggle('hidden', hidden);
                closedEye.classList.toggle('hidden', !hidden);

                button.setAttribute(
                    'aria-label',
                    hidden ? 'Hide password' : 'Show password'
                );
            });
        });
    </script>

</x-guest-layout>