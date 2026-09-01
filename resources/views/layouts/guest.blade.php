<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'YARA') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap"
        rel="stylesheet"
    />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-slate-900 antialiased">

    <div class="min-h-screen bg-slate-50 lg:grid lg:grid-cols-[1.05fr_0.95fr]">

        {{-- LEFT / BRAND PANEL --}}
        <div class="relative hidden overflow-hidden bg-slate-950 lg:flex lg:flex-col lg:justify-between">

            {{-- Decorative background --}}
            <div class="pointer-events-none absolute inset-0 overflow-hidden">

                <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full
                            bg-yellow-400/10 blur-3xl"></div>

                <div class="absolute -bottom-40 -right-32 h-[30rem] w-[30rem]
                            rounded-full bg-yellow-400/5 blur-3xl"></div>

                <div
                    class="absolute inset-0 opacity-[0.035]"
                    style="
                        background-image:
                            linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px);
                        background-size: 48px 48px;
                    "
                ></div>

            </div>


            {{-- Logo --}}
            <div class="relative z-10 px-12 pt-10 xl:px-16 xl:pt-12">

                <a href="/" class="inline-flex items-center gap-3">

                 <img
    src="{{ asset('assets/yellomind_logo.png') }}"
    alt="Yellomind"
    class="h-11 w-11 object-contain"
>

                    <div>
                        <p class="text-lg font-bold leading-none text-white">
                            YARA
                        </p>

                        <p class="mt-1 text-xs font-medium text-slate-400">
                            by Yellomind Consulting
                        </p>
                    </div>

                </a>

            </div>


            {{-- Main message --}}
            <div class="relative z-10 max-w-2xl px-12 xl:px-16">

                <div class="mb-6 inline-flex items-center gap-2 rounded-full
                            border border-white/10 bg-white/5 px-3.5 py-2">

                    <span class="h-2 w-2 rounded-full bg-yellow-400"></span>

                    <span class="text-xs font-semibold tracking-wide text-slate-300">
                        AI READINESS & TRANSFORMATION
                    </span>

                </div>

                <h1 class="max-w-xl text-4xl font-bold leading-tight tracking-tight text-white xl:text-5xl">
                    Turn AI readiness into
                    <span class="text-yellow-400">
                        strategic action.
                    </span>
                </h1>

                <p class="mt-6 max-w-lg text-base leading-7 text-slate-400 xl:text-lg">
                    Assess your organization's AI maturity, identify strategic
                    priorities and build a structured transformation roadmap.
                </p>

            </div>


            {{-- Bottom feature row --}}
            <div class="relative z-10 px-12 pb-10 xl:px-16 xl:pb-12">

                <div class="grid grid-cols-3 gap-6 border-t border-white/10 pt-7">

                    <div>
                        <p class="text-sm font-semibold text-white">
                            Assess
                        </p>
                        <p class="mt-1 text-xs leading-5 text-slate-500">
                            Measure AI maturity
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-white">
                            Understand
                        </p>
                        <p class="mt-1 text-xs leading-5 text-slate-500">
                            Identify priorities
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-white">
                            Transform
                        </p>
                        <p class="mt-1 text-xs leading-5 text-slate-500">
                            Build your roadmap
                        </p>
                    </div>

                </div>

            </div>

        </div>


        {{-- RIGHT / AUTH PANEL --}}
        <div class="flex min-h-screen items-center justify-center px-5 py-10
                    sm:px-8 lg:px-12 xl:px-20">

            <div class="w-full max-w-md">

                {{-- Mobile branding --}}
                <div class="mb-10 lg:hidden">

                    <a href="/" class="inline-flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center
                                    rounded-xl bg-slate-950 font-black text-yellow-400">
                            Y
                        </div>

                        <div>
                            <p class="font-bold leading-none text-slate-950">
                                YARA
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                by Yellomind Consulting
                            </p>
                        </div>

                    </a>

                </div>


                {{-- Authentication page --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white
                            p-6 shadow-sm sm:p-8">

                    {{ $slot }}

                </div>


                {{-- Footer --}}
                <p class="mt-6 text-center text-xs text-slate-400">
                    YARA · AI Readiness & Transformation Platform
                </p>

            </div>

        </div>

    </div>

</body>
</html>