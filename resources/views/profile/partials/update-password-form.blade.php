<section>

    {{-- Header --}}
    <header class="mb-7">
        <h2 class="text-2xl font-bold tracking-tight text-slate-950">
            Update password
        </h2>

        <p class="mt-2 text-sm leading-6 text-slate-500">
            Use a strong, unique password to keep your YARA account secure.
        </p>
    </header>


    <form
        method="post"
        action="{{ route('password.update') }}"
        class="space-y-5"
    >
        @csrf
        @method('put')


        {{-- Current Password --}}
        <div>
            <label
                for="current_password"
                class="mb-2 block text-sm font-semibold text-slate-700"
            >
                Current password
            </label>

            <div class="relative">

                <input
                    id="current_password"
                    name="current_password"
                    type="password"
                    autocomplete="current-password"
                    placeholder="Enter your current password"
                    class="block w-full rounded-xl border border-slate-200
                           bg-white px-4 py-3 pr-12 text-sm text-slate-900
                           shadow-sm outline-none transition
                           focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                >

                <button
                    type="button"
                    data-password-toggle="current_password"
                    aria-label="Show password"
                    class="absolute right-3.5 top-1/2 -translate-y-1/2
                           rounded-md p-1 text-slate-400 transition
                           hover:text-slate-700"
                >
                    <svg
                        data-eye-open
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>

                    <svg
                        data-eye-closed
                        class="hidden h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 4.6A10.6 10.6 0 0112 4.4c4.8 0 8.9 3 10.3 7.6M6.6 6.6A10.8 10.8 0 001.7 12c1.4 4.6 5.5 7.6 10.3 7.6a10.6 10.6 0 004.2-.9"
                        />
                    </svg>
                </button>

            </div>

            @if($errors->updatePassword->get('current_password'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->updatePassword->first('current_password') }}
                </p>
            @endif
        </div>


        {{-- New Password --}}
        <div>
            <label
                for="password"
                class="mb-2 block text-sm font-semibold text-slate-700"
            >
                New password
            </label>

            <div class="relative">

                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="new-password"
                    placeholder="Create a new password"
                    class="block w-full rounded-xl border border-slate-200
                           bg-white px-4 py-3 pr-12 text-sm text-slate-900
                           shadow-sm outline-none transition
                           focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                >

                <button
                    type="button"
                    data-password-toggle="password"
                    aria-label="Show password"
                    class="absolute right-3.5 top-1/2 -translate-y-1/2
                           rounded-md p-1 text-slate-400 transition
                           hover:text-slate-700"
                >
                    <svg
                        data-eye-open
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>

                    <svg
                        data-eye-closed
                        class="hidden h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 4.6A10.6 10.6 0 0112 4.4c4.8 0 8.9 3 10.3 7.6M6.6 6.6A10.8 10.8 0 001.7 12c1.4 4.6 5.5 7.6 10.3 7.6a10.6 10.6 0 004.2-.9"
                        />
                    </svg>
                </button>

            </div>

            @if($errors->updatePassword->get('password'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->updatePassword->first('password') }}
                </p>
            @endif
        </div>


        {{-- Confirm Password --}}
        <div>
            <label
                for="password_confirmation"
                class="mb-2 block text-sm font-semibold text-slate-700"
            >
                Confirm new password
            </label>

            <div class="relative">

                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    placeholder="Repeat your new password"
                    class="block w-full rounded-xl border border-slate-200
                           bg-white px-4 py-3 pr-12 text-sm text-slate-900
                           shadow-sm outline-none transition
                           focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                >

                <button
                    type="button"
                    data-password-toggle="password_confirmation"
                    aria-label="Show password"
                    class="absolute right-3.5 top-1/2 -translate-y-1/2
                           rounded-md p-1 text-slate-400 transition
                           hover:text-slate-700"
                >
                    <svg
                        data-eye-open
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>

                    <svg
                        data-eye-closed
                        class="hidden h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 4.6A10.6 10.6 0 0112 4.4c4.8 0 8.9 3 10.3 7.6M6.6 6.6A10.8 10.8 0 001.7 12c1.4 4.6 5.5 7.6 10.3 7.6a10.6 10.6 0 004.2-.9"
                        />
                    </svg>
                </button>

            </div>

            @if($errors->updatePassword->get('password_confirmation'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </p>
            @endif
        </div>


        {{-- Actions --}}
        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center">

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center
                       rounded-xl bg-slate-950 px-6 py-3 text-sm
                       font-semibold text-white shadow-sm transition
                       hover:bg-slate-800 focus:outline-none
                       focus:ring-4 focus:ring-slate-200 sm:w-auto"
            >
                Update password
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    role="status"
                    aria-live="polite"
                    class="text-sm font-medium text-emerald-600"
                >
                    Password updated successfully.
                </p>
            @endif

        </div>

    </form>


    {{-- Show / hide passwords --}}
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach(function (button) {

            button.addEventListener('click', function () {

                const input = document.getElementById(
                    button.dataset.passwordToggle
                );

                const eyeOpen = button.querySelector('[data-eye-open]');
                const eyeClosed = button.querySelector('[data-eye-closed]');

                if (!input) {
                    return;
                }

                const isHidden = input.type === 'password';

                input.type = isHidden ? 'text' : 'password';

                eyeOpen?.classList.toggle('hidden', isHidden);
                eyeClosed?.classList.toggle('hidden', !isHidden);

                button.setAttribute(
                    'aria-label',
                    isHidden ? 'Hide password' : 'Show password'
                );
            });

        });
    </script>

</section>