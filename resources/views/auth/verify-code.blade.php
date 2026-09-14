<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-semibold text-gray-900">
            Verify your email
        </h2>

        <p class="mt-2 text-sm text-gray-600">
            We sent a 6-digit verification code to
        </p>

        <p class="mt-1 text-sm font-medium text-gray-900">
            {{ auth()->user()->email }}
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first('code') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.code.store') }}">
        @csrf

        <div>
            <label for="code"
                   class="block text-sm font-medium text-gray-700">
                Verification code
            </label>

            <input
                id="code"
                name="code"
                type="text"
                inputmode="numeric"
                pattern="[0-9]*"
                maxlength="6"
                autocomplete="one-time-code"
                autofocus
                required
                placeholder="000000"
                class="mt-2 block w-full rounded-lg border-gray-300
                       text-center text-2xl font-semibold tracking-[0.5em]
                       shadow-sm focus:border-yellow-500
                       focus:ring-yellow-500"
            >
        </div>

        <button
            type="submit"
            class="mt-6 w-full rounded-lg bg-yellow-400 px-4 py-3
                   font-semibold text-gray-900 transition
                   hover:bg-yellow-500"
        >
            Verify email
        </button>
    </form>

    <div class="mt-5 text-center">

    @if (session('status'))
        <p class="mb-3 text-sm font-medium text-green-600">
            {{ session('status') }}
        </p>
    @endif
@if (session('resend_error'))
    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">
        <p class="text-sm font-medium text-amber-700">
            {{ session('resend_error') }}
        </p>
    </div>
@endif
    <p class="text-sm text-gray-500">
        Didn't receive the code?
    </p>

    <form method="POST"
          action="{{ route('verification.code.resend') }}"
          class="mt-2">
        @csrf

        <button
            type="submit"
            class="text-sm font-semibold text-yellow-600
                   hover:text-yellow-700 hover:underline"
        >
            Resend verification code
        </button>
    </form>

    <p class="mt-3 text-xs text-gray-400">
        Verification codes expire after 10 minutes.
    </p>

</div>
</x-guest-layout>