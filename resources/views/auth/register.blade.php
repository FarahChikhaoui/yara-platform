<x-guest-layout>

    <div class="mb-6 text-center">

        <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
            Yellomind Consulting
        </p>

        <h1 class="text-3xl font-bold text-slate-950">
            Create your YARA account
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Create an account to assess your organization's AI readiness.
        </p>

    </div>


    {{-- Social login --}}
    <div class="space-y-3">

        <a href="{{ route('auth.redirect', 'google') }}"
           class="flex w-full items-center justify-center gap-3 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">

            <svg class="h-5 w-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.99.66-2.25 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.85A10.99 10.99 0 0 0 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.1a6.6 6.6 0 0 1 0-4.2V7.05H2.18a11 11 0 0 0 0 9.9l3.66-2.85z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.05l3.66 2.85C6.71 7.31 9.14 5.38 12 5.38z"/>
            </svg>

            Continue with Google
        </a>


        <a href="{{ route('auth.redirect', 'linkedin') }}"
           class="flex w-full items-center justify-center gap-3 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">

            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="#0A66C2">
                <path d="M20.45 20.45h-3.55v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.36V9h3.41v1.56h.05c.47-.9 1.63-1.85 3.36-1.85 3.6 0 4.27 2.37 4.27 5.45v6.29zM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12zM7.12 20.45H3.56V9h3.56v11.45z"/>
            </svg>

            Continue with LinkedIn
        </a>

    </div>


    {{-- Divider --}}
    <div class="my-6 flex items-center gap-3">

        <div class="h-px flex-1 bg-slate-200"></div>

        <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">
            or register with email
        </span>

        <div class="h-px flex-1 bg-slate-200"></div>

    </div>


    {{-- Registration form --}}
    <form method="POST" action="{{ route('register') }}">
        @csrf


        {{-- First name + Last name --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

            {{-- First name --}}
            <div>

                <x-input-label
                    for="first_name"
                    :value="__('First name')"
                />

                <x-text-input
                    id="first_name"
                    class="mt-1 block w-full"
                    type="text"
                    name="first_name"
                    :value="old('first_name')"
                    required
                    autofocus
                    autocomplete="given-name"
                />

                <x-input-error
                    :messages="$errors->get('first_name')"
                    class="mt-2"
                />

            </div>


            {{-- Last name --}}
            <div>

                <x-input-label
                    for="last_name"
                    :value="__('Last name')"
                />

                <x-text-input
                    id="last_name"
                    class="mt-1 block w-full"
                    type="text"
                    name="last_name"
                    :value="old('last_name')"
                    required
                    autocomplete="family-name"
                />

                <x-input-error
                    :messages="$errors->get('last_name')"
                    class="mt-2"
                />

            </div>

        </div>


        {{-- Email --}}
        <div class="mt-4">

            <x-input-label
                for="email"
                :value="__('Email')"
            />

            <x-text-input
                id="email"
                class="mt-1 block w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="email"
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />

        </div>


        {{-- Password --}}
        <div class="mt-4">

            <x-input-label
                for="password"
                :value="__('Password')"
            />

            <x-text-input
                id="password"
                class="mt-1 block w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />

        </div>


        {{-- Confirm Password --}}
        <div class="mt-4">

            <x-input-label
                for="password_confirmation"
                :value="__('Confirm Password')"
            />

            <x-text-input
                id="password_confirmation"
                class="mt-1 block w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->get('password_confirmation')"
                class="mt-2"
            />

        </div>


        {{-- Bottom actions --}}
        <div class="mt-6 flex items-center justify-between">

            <a
                class="text-sm text-slate-600 transition hover:text-yellow-600"
                href="{{ route('login') }}"
            >
                Already have an account?
            </a>

            <x-primary-button class="bg-slate-950 hover:bg-slate-800">
                Create account
            </x-primary-button>

        </div>

    </form>

</x-guest-layout>