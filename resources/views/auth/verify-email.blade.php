<x-guest-layout>

    <div class="w-full">

        {{-- Header --}}
        <div class="mb-7">
            <div class="mb-4 flex h-11 w-11 items-center justify-center
                        rounded-xl bg-yellow-50 text-yellow-600">

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
                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
                    />
                </svg>

            </div>

            <h1 class="text-3xl font-bold tracking-tight text-slate-950">
                Verify your email
            </h1>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                We've sent a verification link to your email address.
                Open the email and follow the link to activate your YARA account.
            </p>
        </div>


        {{-- Success --}}
        @if (session('status') == 'verification-link-sent')

            <div class="mb-6 rounded-xl border border-emerald-200
                        bg-emerald-50 px-4 py-3">

                <div class="flex items-start gap-3">

                    <svg
                        class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600"
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

                    <p class="text-sm leading-6 text-emerald-700">
                        A new verification email has been sent.
                        Please check your inbox.
                    </p>

                </div>

            </div>

        @endif


        {{-- Resend --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button
                type="submit"
                class="group flex w-full items-center justify-center gap-2
                       rounded-xl bg-slate-950 px-5 py-3.5
                       text-sm font-semibold text-white shadow-sm
                       transition hover:bg-slate-800
                       focus:outline-none focus:ring-4 focus:ring-slate-200"
            >
                Resend verification email

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


        {{-- Help --}}
        <div class="mt-6 rounded-xl bg-slate-50 px-4 py-3">
            <p class="text-xs leading-5 text-slate-500">
                Didn't receive the email? Check your spam folder or request
                another verification email above.
            </p>
        </div>


        {{-- Logout --}}
        <div class="mt-7 border-t border-slate-100 pt-6 text-center">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="text-sm font-semibold text-slate-500
                           transition hover:text-slate-950"
                >
                    Sign out
                </button>
            </form>

        </div>

    </div>

</x-guest-layout>