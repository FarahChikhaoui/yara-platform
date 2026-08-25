<x-guest-layout>
    <div class="mb-6 text-center">
        <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
    Yellomind Consulting
</p>
        <h1 class="text-3xl font-bold text-slate-950">Welcome to YARA</h1>
        
        <p class="mt-2 text-sm text-slate-500">
            Sign in to access the AI Readiness Assessment platform.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full"
                          type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded border-gray-300 text-yellow-500 shadow-sm focus:ring-yellow-500"
                       name="remember">
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-slate-600 hover:text-yellow-600" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif

            <x-primary-button class="bg-slate-950 hover:bg-slate-800">
                Sign in
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>