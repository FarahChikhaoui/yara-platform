<section>

    {{-- Header --}}
    <header class="mb-7">
        <h2 class="text-2xl font-bold tracking-tight text-slate-950">
            Profile information
        </h2>

        <p class="mt-2 text-sm leading-6 text-slate-500">
            Manage your personal information and account email.
        </p>
    </header>


    {{-- Verification form --}}
    <form
        id="send-verification"
        method="post"
        action="{{ route('verification.send') }}"
    >
        @csrf
    </form>


    {{-- Profile Form --}}
    <form
    method="post"
    action="{{ route('profile.update') }}"
    enctype="multipart/form-data"
    class="space-y-5"
>
        @csrf
        @method('patch')
{{-- Profile photo --}}
<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">
        Profile photo
    </label>

    <div class="flex flex-col gap-2 sm:flex-row sm:items-center">

        <label
            for="profile_photo"
            class="inline-flex cursor-pointer items-center justify-center gap-2
                   rounded-xl border border-slate-200 bg-white px-4 py-2.5
                   text-sm font-semibold text-slate-700 shadow-sm
                   transition hover:border-slate-300 hover:bg-slate-50"
        >
            <svg
                class="h-5 w-5 text-slate-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 16.5V3.75m0 0l-4.5 4.5M12 3.75l4.5 4.5M6.75 12.75v6a1.5 1.5 0 001.5 1.5h7.5a1.5 1.5 0 001.5-1.5v-6"
                />
            </svg>

            {{ $user->profile_photo ? 'Change photo' : 'Upload photo' }}
        </label>

        <span
            id="profile-photo-name"
            class="text-xs text-slate-400"
        >
            JPG, PNG or WebP · Max 2 MB
        </span>

    </div>

    <input
        id="profile_photo"
        name="profile_photo"
        type="file"
        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
        class="hidden"
    >

    @error('profile_photo')
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>

        {{-- Name --}}
        <div>
            <label
                for="name"
                class="mb-2 block text-sm font-semibold text-slate-700"
            >
                Name
            </label>

            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"

                @error('name')
                    aria-invalid="true"
                    aria-describedby="name-error"
                @enderror

                class="block w-full rounded-xl border border-slate-200
                       bg-white px-4 py-3 text-sm text-slate-900
                       shadow-sm outline-none transition
                       focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
            >

            @error('name')
                <p id="name-error" class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>


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
                    name="email"
                    type="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    autocomplete="username"

                    @error('email')
                        aria-invalid="true"
                        aria-describedby="email-error"
                    @enderror

                    class="block w-full rounded-xl border border-slate-200
                           bg-white py-3 pl-11 pr-4 text-sm text-slate-900
                           shadow-sm outline-none transition
                           focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                >

            </div>

            @error('email')
                <p id="email-error" class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror


            {{-- Unverified email --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())

                <div class="mt-4 rounded-xl border border-yellow-200 bg-yellow-50 p-4">

                    <p class="text-sm leading-6 text-yellow-800">
                        Your email address has not been verified.

                        <button
                            form="send-verification"
                            class="font-semibold underline decoration-yellow-400
                                   underline-offset-2 transition hover:text-yellow-950"
                        >
                            Resend verification email
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')

                        <p
                            role="status"
                            class="mt-2 text-sm font-medium text-emerald-600"
                        >
                            A new verification email has been sent.
                        </p>

                    @endif

                </div>

            @endif

        </div>


        {{-- Actions --}}
        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center">

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center rounded-xl
                       bg-slate-950 px-6 py-3 text-sm font-semibold text-white
                       shadow-sm transition hover:bg-slate-800
                       focus:outline-none focus:ring-4 focus:ring-slate-200
                       sm:w-auto"
            >
                Save changes
            </button>


            @if (session('status') === 'profile-updated')

                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    role="status"
                    aria-live="polite"
                    class="text-sm font-medium text-emerald-600"
                >
                    Changes saved successfully.
                </p>

            @endif

        </div>

    </form>

</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('profile_photo');
        const preview = document.getElementById('profile-photo-preview');
        const initial = document.getElementById('profile-photo-initial');
        const fileName = document.getElementById('profile-photo-name');

        if (!input || !preview) {
            return;
        }

        input.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (!file) {
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');

                if (initial) {
                    initial.classList.add('hidden');
                }

                if (fileName) {
                    fileName.textContent = file.name;
                }
            };

            reader.readAsDataURL(file);
        });
    });
</script>