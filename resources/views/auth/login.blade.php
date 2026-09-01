<x-guest-layout>

    <div class="w-full">

        {{-- Brand --}}
        <div class="mb-8">

            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-yellow-400 shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                </svg>
            </div>

            <h1 class="text-3xl font-bold tracking-tight text-slate-950">
                Welcome back
            </h1>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Sign in to continue to your AI Readiness workspace.
            </p>
        </div>


        {{-- Session Status --}}
        <x-auth-session-status
            class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"
            :status="session('status')"
        />


        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

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
                        class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="you@company.com"
                        class="block w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4
                               text-sm text-slate-900 placeholder:text-slate-400
                               shadow-sm outline-none transition
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
                <div class="mb-2 flex items-center justify-between">

                    <label
                        for="password"
                        class="block text-sm font-semibold text-slate-700"
                    >
                        Password
                    </label>

                    @if (Route::has('password.request'))
                        <a
                            href="{{ route('password.request') }}"
                            class="text-xs font-semibold text-slate-500 transition hover:text-yellow-600"
                        >
                            Forgot password?
                        </a>
                    @endif

                </div>

                <div class="relative">
                    <svg
                        class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                        class="block w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-11
                               text-sm text-slate-900 placeholder:text-slate-400
                               shadow-sm outline-none transition
                               focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                    >

                    <button
                        type="button"
                        id="toggle-password"
                        aria-label="Show password"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 rounded-md p-1 text-slate-400 transition hover:text-slate-700"
                    >
                        <svg id="eye-open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                        <svg id="eye-closed" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88" />
                        </svg>
                    </button>
                </div>

                <x-input-error
                    :messages="$errors->get('password')"
                    class="mt-2"
                />
            </div>


            {{-- Remember Me --}}
            <div class="flex items-center">
                <label
                    for="remember_me"
                    class="flex cursor-pointer items-center gap-2.5"
                >
                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        class="h-4 w-4 rounded border-slate-300 text-yellow-500
                               focus:ring-yellow-400 focus:ring-offset-0"
                    >

                    <span class="text-sm text-slate-600">
                        Remember me
                    </span>
                </label>
            </div>


            {{-- Submit --}}
            <button
                type="submit"
                class="group flex w-full items-center justify-center gap-2 rounded-xl
                       bg-slate-950 px-5 py-3.5 text-sm font-semibold text-white
                       shadow-sm transition
                       hover:bg-slate-800
                       focus:outline-none focus:ring-4 focus:ring-slate-200"
            >
                Sign in

                <svg
                    class="h-4 w-4 transition-transform group-hover:translate-x-0.5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 5l7 7-7 7"
                    />
                </svg>
            </button>

        </form>


        {{-- Bottom --}}
        @if (Route::has('register'))
            <div class="mt-7 border-t border-slate-100 pt-6 text-center">
                <p class="text-sm text-slate-500">
                    New to YARA?

                    <a
                        href="{{ route('register') }}"
                        class="ml-1 font-semibold text-slate-950 transition hover:text-yellow-600"
                    >
                        Create an account
                    </a>
                </p>
            </div>
        @endif

    </div>

    <script>
        (function () {
            const toggleBtn = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            toggleBtn?.addEventListener('click', function () {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';

                eyeOpen.classList.toggle('hidden', isPassword);
                eyeClosed.classList.toggle('hidden', !isPassword);

                toggleBtn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
            });
        })();
    </script>

</x-guest-layout>
