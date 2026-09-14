<x-guest-layout>

    <div class="w-full">

        {{-- Header --}}
        <div class="mb-7">
            <h1 class="text-3xl font-bold tracking-tight text-slate-950">
                Forgot your password?
            </h1>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                No worries. Enter the email address associated with your
                account and we'll send you a link to reset your password.
            </p>
        </div>


        {{-- Session Status --}}
        <x-auth-session-status
            class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50
                   px-4 py-3 text-sm text-emerald-700"
            :status="session('status')"
        />


        {{-- Reset Form --}}
        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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

                    {{-- Email icon --}}
                    <svg
                        class="pointer-events-none absolute left-4 top-1/2
                               h-5 w-5 -translate-y-1/2 text-slate-400"
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
                        autofocus
                        autocomplete="email"
                        placeholder="you@company.com"
                        class="block w-full rounded-xl border border-slate-200
                               bg-white py-3 pl-11 pr-4 text-sm text-slate-900
                               placeholder:text-slate-400 shadow-sm outline-none
                               transition focus:border-yellow-400
                               focus:ring-4 focus:ring-yellow-100"
                    >

                </div>

                <x-input-error
                    :messages="$errors->get('email')"
                    class="mt-2"
                />
            </div>


            {{-- Submit --}}
            <button
                type="submit"
                class="group flex w-full items-center justify-center gap-2
                       rounded-xl bg-slate-950 px-5 py-3.5 text-sm font-semibold
                       text-white shadow-sm transition hover:bg-slate-800
                       focus:outline-none focus:ring-4 focus:ring-slate-200"
            >
                Send reset link

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


        {{-- Back to login --}}
        <div class="mt-7 border-t border-slate-100 pt-6 text-center">
            <p class="text-sm text-slate-500">
                Remember your password?

                <a
                    href="{{ route('login') }}"
                    class="ml-1 font-semibold text-slate-950
                           transition hover:text-yellow-600"
                >
                    Back to sign in
                </a>
            </p>
        </div>

    </div>

</x-guest-layout>