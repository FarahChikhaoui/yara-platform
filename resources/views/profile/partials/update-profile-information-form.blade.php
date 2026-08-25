<section>
    <header>
        <h2 class="text-xl font-bold text-slate-950">
            Profile Information
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Update your account name and email address.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                Name
            </label>

            <input id="name"
                   name="name"
                   type="text"
                   value="{{ old('name', $user->name) }}"
                   required
                   autofocus
                   autocomplete="name"
                   @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
                   class="w-full rounded-xl border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-yellow-400 focus:ring-yellow-400">

            @error('name')
                <p id="name-error" class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                Email
            </label>

            <input id="email"
                   name="email"
                   type="email"
                   value="{{ old('email', $user->email) }}"
                   required
                   autocomplete="username"
                   @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                   class="w-full rounded-xl border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-yellow-400 focus:ring-yellow-400">

            @error('email')
                <p id="email-error" class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-xl border border-yellow-200 bg-yellow-50 p-4">
                    <p class="text-sm text-yellow-800">
                        Your email address is unverified.

                        <button form="send-verification"
                                class="font-semibold underline hover:text-yellow-900">
                            Resend verification email
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p role="status" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="rounded-xl bg-slate-950 px-6 py-3 font-semibold text-white transition hover:bg-slate-800">
                Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    role="status"
                    aria-live="polite"
                    class="text-sm font-medium text-green-600"
                >
                    Saved.
                </p>
            @endif
        </div>
    </form>
</section>