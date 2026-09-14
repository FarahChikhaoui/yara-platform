<section>

    {{-- Header --}}
    <header class="mb-6">
        <div class="flex items-start gap-3">

            <div class="flex h-10 w-10 shrink-0 items-center justify-center
                        rounded-xl bg-red-50 text-red-600">
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
                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"
                    />
                </svg>
            </div>

            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-950">
                    Delete account
                </h2>

                <p class="mt-1 text-sm leading-6 text-slate-500">
                    Permanently delete your YARA account and associated data.
                    This action cannot be undone.
                </p>
            </div>

        </div>
    </header>


    {{-- Warning --}}
    <div class="mb-6 rounded-xl border border-red-100 bg-red-50/60 px-4 py-3">
        <p class="text-sm leading-6 text-red-700">
            Once your account is deleted, you will permanently lose access
            to your assessments, results and account information.
        </p>
    </div>


    {{-- Delete button --}}
    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex w-full items-center justify-center gap-2 rounded-xl
               bg-red-600 px-6 py-3 text-sm font-semibold text-white
               shadow-sm transition hover:bg-red-700
               focus:outline-none focus:ring-4 focus:ring-red-100
               sm:w-auto"
    >
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
                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.166L18.16 19.673A2.25 2.25 0 0115.916 21H8.084a2.25 2.25 0 01-2.244-1.327L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0V4.477c0-1.08-.835-1.977-1.913-2.013a51.964 51.964 0 00-3.674 0C9.085 2.5 8.25 3.397 8.25 4.477v.916m7.5 0a48.667 48.667 0 00-7.5 0"
            />
        </svg>

        Delete account
    </button>


    {{-- Confirmation Modal --}}
    <x-modal
        name="confirm-user-deletion"
        :show="$errors->userDeletion->isNotEmpty()"
        focusable
    >

        <form
            method="post"
            action="{{ route('profile.destroy') }}"
            class="p-6 sm:p-8"
        >
            @csrf
            @method('delete')


            {{-- Modal icon --}}
            <div class="flex h-12 w-12 items-center justify-center
                        rounded-2xl bg-red-50 text-red-600">

                <svg
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"
                    />
                </svg>

            </div>


            <h2 class="mt-5 text-2xl font-bold tracking-tight text-slate-950">
                Delete your account?
            </h2>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                This will permanently delete your account and associated data.
                Enter your password to confirm that you want to continue.
            </p>


            {{-- Password --}}
            <div class="mt-6">

                <label
                    for="delete_account_password"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Password
                </label>

                <div class="relative">

                    <input
                        id="delete_account_password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        placeholder="Enter your password"
                        class="block w-full rounded-xl border border-slate-200
                               bg-white px-4 py-3 pr-12 text-sm text-slate-900
                               shadow-sm outline-none transition
                               focus:border-red-400 focus:ring-4 focus:ring-red-100"
                    >

                    <button
                        type="button"
                        id="toggle-delete-password"
                        aria-label="Show password"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2
                               rounded-md p-1 text-slate-400 transition
                               hover:text-slate-700"
                    >
                        <svg
                            id="delete-eye-open"
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
                            id="delete-eye-closed"
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

                @if($errors->userDeletion->get('password'))
                    <p class="mt-2 text-sm font-medium text-red-600">
                        {{ $errors->userDeletion->first('password') }}
                    </p>
                @endif

            </div>


            {{-- Actions --}}
            <div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="inline-flex items-center justify-center rounded-xl
                           border border-slate-200 bg-white px-5 py-2.5
                           text-sm font-semibold text-slate-700
                           transition hover:bg-slate-50"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl
                           bg-red-600 px-5 py-2.5 text-sm font-semibold
                           text-white transition hover:bg-red-700
                           focus:outline-none focus:ring-4 focus:ring-red-100"
                >
                    Permanently delete account
                </button>

            </div>

        </form>

    </x-modal>


    {{-- Password visibility --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const toggle = document.getElementById('toggle-delete-password');
            const input = document.getElementById('delete_account_password');
            const eyeOpen = document.getElementById('delete-eye-open');
            const eyeClosed = document.getElementById('delete-eye-closed');

            toggle?.addEventListener('click', function () {

                const hidden = input.type === 'password';

                input.type = hidden ? 'text' : 'password';

                eyeOpen?.classList.toggle('hidden', hidden);
                eyeClosed?.classList.toggle('hidden', !hidden);

                toggle.setAttribute(
                    'aria-label',
                    hidden ? 'Hide password' : 'Show password'
                );

            });

        });
    </script>

</section>