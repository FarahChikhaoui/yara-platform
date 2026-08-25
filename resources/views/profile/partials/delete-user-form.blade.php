<section class="space-y-6">
    <header>
        <h2 class="text-xl font-bold text-red-700">
            Delete Account
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Permanently delete your account and associated data. This action cannot be undone.
        </p>
    </header>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-red-700 transition"
    >
        Delete Account
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-bold text-slate-950">
                Confirm Account Deletion
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                This will permanently delete your account and related data. Enter your password to confirm.
            </p>

            <div class="mt-6">
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                    Password
                </label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Enter your password"
                    class="w-full rounded-xl border-slate-300 focus:border-red-400 focus:ring-red-400"
                >

                @if($errors->userDeletion->get('password'))
                    <p class="mt-2 text-sm text-red-600">
                        {{ $errors->userDeletion->first('password') }}
                    </p>
                @endif
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="px-5 py-2.5 rounded-xl border border-slate-300 font-semibold hover:bg-slate-50"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700"
                >
                    Delete Account
                </button>
            </div>
        </form>
    </x-modal>
</section>