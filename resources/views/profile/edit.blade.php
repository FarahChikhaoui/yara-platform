@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.yara')

@section('content')

<div class="space-y-8">

    <div>

        <div class="flex items-center gap-4">

            @if(auth()->user()->role === 'admin')
                <a href="/admin/dimensions"
                   class="inline-flex items-center justify-center text-slate-500 hover:text-slate-950 transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="30"
                         height="30"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M6 8L2 12L6 16"/>
                        <path d="M2 12H22"/>
                    </svg>

                </a>
            @endif

            @if(auth()->user()->role === 'client')
                <a href="/dashboard"
                   class="inline-flex items-center justify-center text-slate-500 hover:text-slate-950 transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="30"
                         height="30"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M6 8L2 12L6 16"/>
                        <path d="M2 12H22"/>
                    </svg>

                </a>
            @endif

            <div>
                <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">
                    Account Settings
                </p>

                <h1 class="text-4xl font-bold text-slate-950">
                    Profile
                </h1>
            </div>

        </div>

        <p class="mt-4 text-slate-500">
            Manage your account information and security settings.
        </p>

    </div>

    <div class="max-w-4xl">

        <div class="space-y-6">

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">

                <div class="flex items-center gap-5 mb-8">

    {{-- Profile photo --}}
    <div class="relative shrink-0">

        <div class="w-20 h-20 rounded-full overflow-hidden
                    bg-yellow-400 border-4 border-white shadow-sm
                    flex items-center justify-center">

            @if(Auth::user()->profile_photo)

                <img
                    id="profile-photo-preview"
                    src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                    alt="{{ Auth::user()->name }}"
                    class="w-full h-full object-cover"
                >

                <span
                    id="profile-photo-initial"
                    class="hidden text-slate-950 font-bold text-2xl"
                >
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>

            @else

                <img
                    id="profile-photo-preview"
                    src=""
                    alt=""
                    class="hidden w-full h-full object-cover"
                >

                <span
                    id="profile-photo-initial"
                    class="text-slate-950 font-bold text-2xl"
                >
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>

            @endif

        </div>

    </div>

    {{-- User information --}}
    <div class="min-w-0">

        <p class="text-xl font-bold text-slate-950">
            {{ Auth::user()->name }}
        </p>

        <p class="truncate text-slate-500">
            {{ Auth::user()->email }}
        </p>

        <span class="inline-flex mt-2 bg-slate-950 text-white text-xs font-bold px-3 py-1 rounded-full">
            {{ ucfirst(Auth::user()->role) }}
        </span>

    </div>

</div>

                <div class="grid grid-cols-1 {{ Auth::user()->role === 'admin' ? 'md:grid-cols-2' : 'md:grid-cols-3' }} gap-4 mb-8">

                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs font-bold uppercase text-slate-500">
                            Role
                        </p>
                        <p class="mt-2 font-semibold text-slate-950">
                            {{ ucfirst(Auth::user()->role) }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs font-bold uppercase text-slate-500">
                            Account Created
                        </p>
                        <p class="mt-2 font-semibold text-slate-950">
                            {{ Auth::user()->created_at ? Auth::user()->created_at->format('d M Y') : '—' }}
                        </p>
                    </div>

                    @if(Auth::user()->role !== 'admin')
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-xs font-bold uppercase text-slate-500">
                                Company
                            </p>
                            <p class="mt-2 font-semibold text-slate-950">
                                {{ Auth::user()->company->name ?? '—' }}
                            </p>
                        </div>
                    @endif

                </div>

                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>

            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">

                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>

            </div>

            <div class="bg-white rounded-3xl border border-red-200 shadow-sm p-8">

                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>

            </div>

        </div>

    </div>

</div>

@endsection