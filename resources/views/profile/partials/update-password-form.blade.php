<section>
    <header>
        <h2 class="text-xl font-bold text-slate-950">
            Update Password
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Ensure your account uses a strong and secure password.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-2">
                Current Password
            </label>

            <input id="current_password"
                   name="current_password"
                   type="password"
                   autocomplete="current-password"
                   class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">

            @if($errors->updatePassword->get('current_password'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->updatePassword->first('current_password') }}
                </p>
            @endif
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                New Password
            </label>

            <input id="password"
                   name="password"
                   type="password"
                   autocomplete="new-password"
                   class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">

            @if($errors->updatePassword->get('password'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->updatePassword->first('password') }}
                </p>
            @endif
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                Confirm Password
            </label>

            <input id="password_confirmation"
                   name="password_confirmation"
                   type="password"
                   autocomplete="new-password"
                   class="w-full rounded-xl border-slate-300 focus:border-yellow-400 focus:ring-yellow-400">

            @if($errors->updatePassword->get('password_confirmation'))
                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </p>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">

            <button type="submit"
                    class="bg-slate-950 text-white px-6 py-3 rounded-xl font-semibold hover:bg-slate-800 transition">
                Update Password
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium"
                >
                    Password updated.
                </p>
            @endif

        </div>
    </form>
</section>