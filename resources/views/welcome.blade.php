<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>YARA Platform</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="bg-slate-50 text-slate-900">

<header class="border-b-4 border-yellow-400 bg-slate-950 text-white">

    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 md:px-8">

        {{-- Brand --}}
        <a href="/" class="flex items-center gap-3">

            <img
                src="{{ asset('assets/yellomind_logo.png') }}"
                class="h-12 w-12 object-contain"
                alt="Yellomind"
            >

            <div>
                <h1 class="text-xl font-bold">
                    YARA
                </h1>

                <p class="text-sm text-slate-300">
                    Yellomind · AI Readiness Assessment
                </p>
            </div>

        </a>

        {{-- Desktop navigation --}}
        <nav class="hidden items-center gap-7 text-sm font-medium md:flex">

            <a
                href="#about"
                class="text-slate-300 transition hover:text-yellow-400"
            >
                About
            </a>

            <a
                href="#process"
                class="text-slate-300 transition hover:text-yellow-400"
            >
                How it works
            </a>

            <a
                href="#engagements"
                class="text-slate-300 transition hover:text-yellow-400"
            >
                Services
            </a>

           
            <a
                href="#faq"
                class="text-slate-300 transition hover:text-yellow-400"
            >
                FAQ
            </a>

            <a
                href="/login"
                class="transition hover:text-yellow-400"
            >
                Login
            </a>
<a href="{{ route('register') }}"
   class="rounded-lg bg-yellow-400 px-4 py-2
          font-semibold text-slate-950
          transition hover:bg-yellow-300">
    Sign up
</a>
          

        </nav>

        {{-- Mobile actions --}}
        <div class="flex items-center gap-3 md:hidden">

            <a
                href="/login"
                class="text-sm font-medium transition hover:text-yellow-400"
            >
                Login
            </a>

            <a
                href="/register"
                class="rounded-lg bg-yellow-400 px-4 py-2 text-sm font-semibold text-slate-950"
            >
                Start
            </a>

        </div>

    </div>

</header>

<main>

    {{-- Hero --}}
    <section class="mx-auto grid max-w-7xl items-center gap-12 px-6 py-16 md:px-8 lg:grid-cols-2 lg:py-24">

        <div>

            <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
                AI Readiness Assessment Platform
            </p>

            <h2 class="mt-4 max-w-3xl text-4xl font-bold leading-tight text-slate-950 md:text-5xl lg:text-6xl">
                Evaluate your organization’s readiness for AI transformation.
            </h2>

           <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600">
    YARA helps organizations assess AI maturity across strategy,
    governance, data, technology, culture, adoption and security,
    then translates the findings into executive insights and
    transformation priorities.
</p>

           

            {{-- NOTE: these three stats (dimensions / maturity levels / score scale) are currently
                 hardcoded. Consider pulling "11" from the live count of active dimensions in the DB
                 so this never drifts out of sync with the questionnaire. --}}
            <div class="mt-10 grid max-w-2xl grid-cols-1 gap-4 sm:grid-cols-3">

                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                    <p class="text-2xl font-bold text-slate-950">
                        11
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Assessment dimensions
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                    <p class="text-2xl font-bold text-slate-950">
                        5
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Maturity levels
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                    <p class="text-2xl font-bold text-slate-950">
                        100
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Readiness score scale
                    </p>
                </div>

            </div>

        </div>

        {{-- Evaluation dimensions --}}
        {{-- NOTE: "AI Security & Resilience" is shown here but should be checked against the
             actual 11 dimensions stored in the DB/framework doc — swap in whichever 5 are the
             true headline dimensions if this list has drifted. --}}
        <div class="rounded-3xl border border-slate-200 border-t-4 border-t-yellow-400 bg-white p-7 shadow-sm md:p-8">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
                        Assessment framework
                    </p>

                    <h3 class="mt-2 text-2xl font-bold text-slate-950">
                        What YARA evaluates
                    </h3>
                </div>

                <span class="w-fit rounded-full bg-yellow-100 px-3 py-1 text-sm font-semibold text-yellow-700">
                    Multi-dimensional
                </span>

            </div>

            <div class="mt-7 grid gap-4">

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="font-semibold text-slate-900">
                        AI Strategy & Vision
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Strategic alignment, executive sponsorship and value objectives.
                    </p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="font-semibold text-slate-900">
                        Data Readiness
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Data quality, accessibility, ownership and governance.
                    </p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="font-semibold text-slate-900">
                        AI Governance
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Decision rights, accountability and oversight mechanisms.
                    </p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="font-semibold text-slate-900">
                        Responsible AI & Ethics
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Responsible use, transparency and risk controls.
                    </p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="font-semibold text-slate-900">
                        AI Security & Resilience
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Security, reliability, resilience and operational safeguards.
                    </p>
                </div>

            </div>

        </div>

    </section>

    

    {{-- About --}}
    <section
        id="about"
        class="bg-white"
    >

        <div class="mx-auto max-w-7xl px-6 py-16 md:px-8 lg:py-20">

            <div class="max-w-4xl">

                <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
                    About YARA
                </p>

                <h2 class="mt-3 text-3xl font-bold leading-tight text-slate-950 md:text-4xl">
                    From assessment responses to actionable maturity insights.
                </h2>

                <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">
                    YARA combines automated scoring, maturity classification,
    country benchmarking and AI-powered analysis to help leadership
    teams understand their current position and identify their next priorities.
                </p>

            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-3">

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-950 text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"></circle>
                            <circle cx="12" cy="12" r="4.5"></circle>
                            <circle cx="12" cy="12" r="0.5" fill="currentColor"></circle>
                        </svg>
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-slate-950">
                        Multi-dimensional scoring
                    </h3>

                    <p class="mt-3 leading-7 text-slate-600">
                        Evaluate AI readiness across organizational strategy,
                        data, governance, technology, people and risk dimensions.
                    </p>

                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-950 text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3v18h18"></path>
                            <path d="M7 15l4-5 3 3 5-7"></path>
                        </svg>
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-slate-950">
                        Maturity and benchmarking
                    </h3>

                    <p class="mt-3 leading-7 text-slate-600">
                        Convert assessment responses into maturity levels,
                        readiness scores and country benchmark comparisons.
                    </p>

                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-950 text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 11l3 3L22 4"></path>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                    </div>

                   <h3 class="mt-5 text-lg font-bold text-slate-950">
    Actionable readiness insights
</h3>

<p class="mt-3 leading-7 text-slate-600">
    Identify priority capability gaps and receive clear recommendations
    on where your organization should focus next.
</p>

                </div>

            </div>

        </div>

    </section>

    {{-- Process --}}
    <section
        id="process"
        class="bg-slate-50"
    >

        <div class="mx-auto max-w-7xl px-6 py-16 md:px-8 lg:py-20">

    <div class="max-w-4xl">

        <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
            How it works
        </p>

        <h2 class="mt-3 text-3xl font-bold leading-tight text-slate-950 md:text-4xl">
            From readiness signal to transformation.
        </h2>

        <p class="mt-4 max-w-3xl leading-7 text-slate-600">
            Start with a quick readiness signal, explore your organization
            in depth with the full assessment, then choose whether to turn
            your findings into a structured transformation roadmap.
        </p>

    </div>

    <div class="mt-12 grid gap-6 lg:grid-cols-3">

        {{-- Step 1 --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">

            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-400 text-lg font-bold text-slate-950">
                1
            </span>

            <p class="mt-5 text-sm font-semibold uppercase tracking-wide text-yellow-600">
                Explore
            </p>

            <h3 class="mt-2 text-xl font-bold text-slate-950">
                Get a quick readiness signal
            </h3>

            <p class="mt-3 leading-7 text-slate-600">
                Take the free Pulse Check to get an initial indication of
                your organization's AI readiness, relative strengths and
                areas that may require further attention.
            </p>

        </div>

        {{-- Step 2 --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">

            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-400 text-lg font-bold text-slate-950">
                2
            </span>

            <p class="mt-5 text-sm font-semibold uppercase tracking-wide text-yellow-600">
                Diagnose
            </p>

            <h3 class="mt-2 text-xl font-bold text-slate-950">
                Understand your readiness in depth
            </h3>

            <p class="mt-3 leading-7 text-slate-600">
                Create a free account and complete the full AI Readiness
                Assessment across all 11 dimensions to receive detailed
                scoring, benchmarking, AI-powered insights and recommendations.
            </p>

        </div>

        {{-- Step 3 --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">

            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-950 text-lg font-bold text-yellow-400">
                3
            </span>

            <p class="mt-5 text-sm font-semibold uppercase tracking-wide text-yellow-600">
                Transform
            </p>

            <h3 class="mt-2 text-xl font-bold text-slate-950">
                Turn findings into a roadmap
            </h3>

            <p class="mt-3 leading-7 text-slate-600">
                When you're ready to move from diagnosis to action, define
                your priorities, target maturity, timeline and investment
                capacity and work with Yellomind to build a structured
                transformation roadmap.
            </p>

        </div>

    </div>

    
    </div>

</div>
</section>

    {{-- YARA Engagements --}}
<section
    id="engagements"
    class="border-y border-slate-200 bg-white"
>
    <div class="mx-auto max-w-7xl px-6 py-16 md:px-8 lg:py-20">

       {{-- Section heading --}}
<div class="mx-auto max-w-4xl text-center">

    <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
        Assessment & Transformation Options
    </p>

    <h2 class="mt-3 text-3xl font-bold text-slate-950 md:text-4xl">
        From initial diagnosis to transformation.
    </h2>

    <p class="mx-auto mt-4 max-w-3xl text-lg leading-8 text-slate-600">
        Start with a quick readiness signal, complete the full assessment
        for an in-depth diagnosis, or move from assessment findings to
        a structured transformation roadmap with Yellomind.
    </p>

</div>
        


        {{-- Three engagement cards --}}
        <div class="mt-12 grid gap-6 lg:grid-cols-3">

            {{-- 1. PULSE CHECK --}}
            <article
                class="flex flex-col rounded-3xl border border-slate-200 border-t-4
       border-t-slate-950 bg-white p-7 shadow-md"
            >

                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
    Free · Explore
</p>

                    <h3 class="mt-3 text-2xl font-bold text-slate-950">
                        AI Readiness Pulse Check
                    </h3>

                    <p class="mt-4 leading-7 text-slate-600">
                        Get a quick initial signal of your organization's
AI readiness and identify areas worth exploring in greater depth.
                    </p>
                </div>

                <div class="mt-6">
                    <p class="text-sm font-semibold text-slate-500">
                        Investment
                    </p>

                    <p class="mt-1 text-3xl font-bold text-slate-950">
                        Free
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        No account required · Under 10 minutes
                    </p>
                </div>

                <ul class="mt-6 space-y-3 text-sm text-slate-700">

                    <li class="flex gap-3">
                        <span class="font-bold text-green-600">✓</span>
                        <span>Fast executive readiness signal</span>
                    </li>

                    <li class="flex gap-3">
                        <span class="font-bold text-green-600">✓</span>
                        <span>High-level strengths and capability gaps</span>
                    </li>

                    <li class="flex gap-3">
                        <span class="font-bold text-green-600">✓</span>
                        <span>Initial maturity indication</span>
                    </li>

                    <li class="flex gap-3">
                        <span class="font-bold text-green-600">✓</span>
                        <span>Recommended next step</span>
                    </li>

                </ul>

                <div class="mt-auto pt-8">

                    <form action="{{ route('pulse.start') }}" method="POST">
                        @csrf

                        <button
                            type="submit"
                            class="block w-full rounded-xl bg-slate-950 px-5 py-3
       text-center font-bold text-white transition
       hover:bg-slate-800"
                        >
                            Start Free Pulse Check
                        </button>
                    </form>

                </div>

            </article>


           {{-- 2. AI READINESS ASSESSMENT --}}
<article
    class="relative flex flex-col overflow-hidden rounded-3xl
           border-2 border-yellow-400 bg-white p-7 shadow-lg"
>

    <div
        class="absolute right-0 top-0 rounded-bl-2xl
               bg-yellow-400 px-4 py-2 text-xs font-bold
               uppercase tracking-wide text-slate-950"
    >
        Full Assessment
    </div>

    <div>

        <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
            Diagnose
        </p>

        <h3 class="mt-3 pr-20 text-2xl font-bold text-slate-950">
            AI Readiness Assessment
        </h3>

        <p class="mt-4 leading-7 text-slate-600">
            Assess your organization's AI maturity in depth across the
            complete YARA framework and understand where capability gaps
            and priorities exist.
        </p>

    </div>

    <div class="mt-6">

        <p class="text-sm font-semibold text-slate-500">
            Access
        </p>

        <p class="mt-1 text-3xl font-bold text-slate-950">
            Free
        </p>

        <p class="mt-1 text-sm text-slate-500">
            Account required · Results saved to your workspace
        </p>

    </div>

    <ul class="mt-6 space-y-3 text-sm text-slate-700">

        <li class="flex gap-3">
            <span class="font-bold text-green-600">✓</span>
            <span>Full 11-dimension readiness assessment</span>
        </li>

        <li class="flex gap-3">
            <span class="font-bold text-green-600">✓</span>
            <span>Detailed maturity scoring by dimension</span>
        </li>

        <li class="flex gap-3">
            <span class="font-bold text-green-600">✓</span>
            <span>Country readiness benchmarking</span>
        </li>

        <li class="flex gap-3">
            <span class="font-bold text-green-600">✓</span>
            <span>AI-powered executive analysis</span>
        </li>

        <li class="flex gap-3">
            <span class="font-bold text-green-600">✓</span>
            <span>Prioritized recommendations and next steps</span>
        </li>

        <li class="flex gap-3">
            <span class="font-bold text-green-600">✓</span>
            <span>Downloadable executive report</span>
        </li>

    </ul>

    <div class="mt-auto pt-8">

        <a
href="{{ route('assessment.entry') }}"
            class="block rounded-xl bg-yellow-400 px-5 py-3
                   text-center font-bold text-slate-950
                   transition hover:bg-yellow-300"
        >
            Start Full Assessment
        </a>

    </div>

</article>


           {{-- 3. TRANSFORMATION ROADMAP --}}
<article
    class="flex flex-col rounded-3xl border border-slate-800
           bg-slate-950 p-7 text-white shadow-lg"
>

    <div>

        <p class="text-sm font-semibold uppercase tracking-wide text-yellow-400">
            Transform
        </p>

        <h3 class="mt-3 text-2xl font-bold">
            Transformation Roadmap
        </h3>

        <p class="mt-4 leading-7 text-slate-300">
            Move from diagnosis to action with a structured roadmap
            aligned with your organization's priorities, target maturity,
            timeline and investment capacity.
        </p>

    </div>

    <div class="mt-6">

        <p class="text-sm font-semibold text-slate-400">
            Professional service
        </p>

        <p class="mt-1 text-2xl font-bold">
            Tailored to your organization
        </p>

        <p class="mt-2 text-sm leading-6 text-slate-400">
            Available after completing a full AI Readiness Assessment.
            Scope is defined according to your transformation objectives.
        </p>

    </div>

    <ul class="mt-6 space-y-3 text-sm text-slate-300">

        <li class="flex gap-3">
            <span class="font-bold text-yellow-400">✓</span>
            <span>Consultant review of assessment findings</span>
        </li>

        <li class="flex gap-3">
            <span class="font-bold text-yellow-400">✓</span>
            <span>Target maturity and priority definition</span>
        </li>

        <li class="flex gap-3">
            <span class="font-bold text-yellow-400">✓</span>
            <span>Timeline and investment capacity alignment</span>
        </li>

        <li class="flex gap-3">
            <span class="font-bold text-yellow-400">✓</span>
            <span>Prioritized transformation initiatives</span>
        </li>

        <li class="flex gap-3">
            <span class="font-bold text-yellow-400">✓</span>
            <span>Estimated investment by initiative</span>
        </li>

        <li class="flex gap-3">
            <span class="font-bold text-yellow-400">✓</span>
            <span>Expert-reviewed transformation roadmap</span>
        </li>

    </ul>

    <div class="mt-auto pt-8">

        <a
href="{{ route('transformation.entry') }}"
   class="block rounded-xl border border-yellow-400
                   px-5 py-3 text-center font-bold text-yellow-400
                   transition hover:bg-yellow-400 hover:text-slate-950"
        >
            Build Your Transformation Roadmap
        </a>

    </div>

</article>

        </div>

    </div>
</section>
    

   {{-- FAQ --}}
<section id="faq" class="border-y border-slate-200 bg-white">
    <div class="mx-auto max-w-4xl px-6 py-16 md:px-8 lg:py-20">

        <p class="text-sm font-semibold uppercase tracking-wide text-yellow-600">
            FAQ
        </p>

        <h2 class="mt-3 text-3xl font-bold text-slate-950 md:text-4xl">
            Common questions
        </h2>

        <div class="mt-10 divide-y divide-slate-200 rounded-2xl border border-slate-200">


            {{-- 2. Pulse Check vs Full Assessment --}}
            <details class="group p-6 open:bg-slate-50">
                <summary class="flex cursor-pointer list-none items-center justify-between font-semibold text-slate-950">
                    What's the difference between the Pulse Check and the full AI Readiness Assessment?
                    <span class="ml-4 shrink-0 text-slate-400 transition group-open:rotate-45">
                        +
                    </span>
                </summary>

                <p class="mt-3 leading-7 text-slate-600">
                    The Pulse Check is a short diagnostic that provides an initial
                    readiness signal based on a smaller selection of questions.
                    The full AI Readiness Assessment evaluates all 11 dimensions
                    and provides detailed maturity scoring, benchmarking,
                    AI-powered analysis, prioritized recommendations and a
                    downloadable report.
                </p>
            </details>


           

            {{-- 4. Assessment Results --}}
            <details class="group p-6 open:bg-slate-50">
                <summary class="flex cursor-pointer list-none items-center justify-between font-semibold text-slate-950">
                    What do I receive after completing the full assessment?
                    <span class="ml-4 shrink-0 text-slate-400 transition group-open:rotate-45">
                        +
                    </span>
                </summary>

                <p class="mt-3 leading-7 text-slate-600">
                    You receive an overall readiness score and maturity level,
                    detailed results across the 11 assessment dimensions,
                    country benchmarking, key strengths and capability gaps,
                    AI-powered executive insights, prioritized recommendations
                    and a downloadable executive report.
                </p>
            </details>


            {{-- 5. Roadmap Included? --}}
            <details class="group p-6 open:bg-slate-50">
                <summary class="flex cursor-pointer list-none items-center justify-between font-semibold text-slate-950">
                    Does the free assessment include a transformation roadmap?
                    <span class="ml-4 shrink-0 text-slate-400 transition group-open:rotate-45">
                        +
                    </span>
                </summary>

                <p class="mt-3 leading-7 text-slate-600">
                    No. The assessment is designed to diagnose your organization's
                    current AI readiness and identify the areas that should be
                    prioritized. A transformation roadmap is a separate professional
                    service that turns those findings into structured initiatives
                    aligned with your organization's objectives, target maturity,
                    timeline and investment capacity.
                </p>
            </details>
            {{-- 7. Roadmap Contents --}}
            <details class="group p-6 open:bg-slate-50">
                <summary class="flex cursor-pointer list-none items-center justify-between font-semibold text-slate-950">
                    What does the transformation roadmap contain?
                    <span class="ml-4 shrink-0 text-slate-400 transition group-open:rotate-45">
                        +
                    </span>
                </summary>

                <p class="mt-3 leading-7 text-slate-600">
                    The roadmap translates assessment findings into prioritized
                    transformation initiatives. Each initiative can include its
                    priority, recommended actions, expected timeline, target
                    capability or maturity improvement and indicative investment
                    requirements, providing a structured plan for moving from
                    diagnosis to implementation.
                </p>
            </details>


            {{-- 8. After Roadmap --}}
            <details class="group p-6 open:bg-slate-50">
                <summary class="flex cursor-pointer list-none items-center justify-between font-semibold text-slate-950">
                    What happens after we receive our transformation roadmap?
                    <span class="ml-4 shrink-0 text-slate-400 transition group-open:rotate-45">
                        +
                    </span>
                </summary>

                <p class="mt-3 leading-7 text-slate-600">
                    The roadmap becomes an actionable transformation plan.
                    Your organization can track roadmap initiatives and their
                    implementation status through YARA and choose whether to
                    execute them internally or request additional implementation
                    support from Yellomind.
                </p>
            </details>

            {{-- 10. Yellomind Implementation Support --}}
            <details class="group p-6 open:bg-slate-50">
                <summary class="flex cursor-pointer list-none items-center justify-between font-semibold text-slate-950">
                    What does Yellomind implementation support include?
                    <span class="ml-4 shrink-0 text-slate-400 transition group-open:rotate-45">
                        +
                    </span>
                </summary>

                <p class="mt-3 leading-7 text-slate-600">
                    Implementation support helps turn roadmap initiatives into
                    coordinated workstreams. Depending on the organization's needs,
                    support may include implementation planning, ownership and
                    governance structures, progress monitoring, capability
                    development, change support and ongoing consultant guidance.
                </p>
            </details>


            {{-- 11. Reassessment --}}
            <details class="group p-6 open:bg-slate-50">
                <summary class="flex cursor-pointer list-none items-center justify-between font-semibold text-slate-950">
                    Can we reassess our AI readiness later?
                    <span class="ml-4 shrink-0 text-slate-400 transition group-open:rotate-45">
                        +
                    </span>
                </summary>

                <p class="mt-3 leading-7 text-slate-600">
                    Yes. YARA keeps assessment history so organizations can
                    periodically reassess their AI readiness and compare results
                    over time. This makes it possible to measure maturity
                    progression and identify areas where additional improvement
                    is still required.
                </p>
            </details>
        </div>

    </div>
</section>
</main>

{{-- Back to top --}}
<button
    type="button"
    id="back-to-top"
    aria-label="Back to top"
    title="Back to top"
    class="fixed bottom-7 right-7 z-50 flex h-11 w-11
           translate-y-3 items-center justify-center rounded-full
           border border-slate-200 bg-white text-slate-500
           opacity-0 shadow-lg shadow-slate-900/10
           transition-all duration-200 pointer-events-none
           hover:-translate-y-1 hover:border-yellow-300
           hover:text-yellow-600 print:hidden"
>
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
            d="M5 15l7-7 7 7"
        />
    </svg>
</button>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const backToTop = document.getElementById('back-to-top');

        function updateBackToTop() {
            if (window.scrollY > 500) {
                backToTop.classList.remove(
                    'opacity-0',
                    'pointer-events-none',
                    'translate-y-3'
                );

                backToTop.classList.add(
                    'opacity-100',
                    'pointer-events-auto',
                    'translate-y-0'
                );
            } else {
                backToTop.classList.add(
                    'opacity-0',
                    'pointer-events-none',
                    'translate-y-3'
                );

                backToTop.classList.remove(
                    'opacity-100',
                    'pointer-events-auto',
                    'translate-y-0'
                );
            }
        }

        window.addEventListener('scroll', updateBackToTop);

        backToTop.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        updateBackToTop();
    });
</script>
<footer class="border-t-4 border-yellow-400 bg-slate-950 text-slate-300">
    <div class="mx-auto max-w-7xl px-6 py-14 md:px-8">

        {{-- Rebalanced to three even 3-link columns beside the brand block --}}
        <div class="grid gap-10 md:grid-cols-4">

            {{-- Brand --}}
            <div>

                <div class="flex items-center gap-3">

                    <img
                        src="{{ asset('assets/yellomind_logo.png') }}"
                        class="h-10 w-10 object-contain"
                        alt="Yellomind Logo"
                    >

                    <div>
                        <h3 class="font-bold text-white">
                            YARA 
                        </h3>

                        <p class="text-sm text-slate-400">
                            AI Readiness Assessment
                        </p>
                    </div>

                </div>

                <p class="mt-5 text-sm leading-6 text-slate-400">
                    YARA helps organizations evaluate AI maturity,
                    governance, readiness and transformation capabilities.
                </p>

            </div>

            {{-- Platform --}}
            <div>

                <h3 class="font-semibold text-white">
                    Platform
                </h3>

                <div class="mt-4 flex flex-col gap-3 text-sm">

                    <a
                        href="/register"
                        class="transition hover:text-yellow-400"
                    >
                        Get Started
                    </a>

                    <a
                        href="/login"
                        class="transition hover:text-yellow-400"
                    >
                        Login
                    </a>

                    <a
                        href="#about"
                        class="transition hover:text-yellow-400"
                    >
                        About YARA
                    </a>

                </div>

            </div>

            {{-- Resources --}}
            <div>

                <h3 class="font-semibold text-white">
                    Resources
                </h3>

                <div class="mt-4 flex flex-col gap-3 text-sm">

                    <a
                        href="#process"
                        class="transition hover:text-yellow-400"
                    >
                        How it works
                    </a>

                    <a
                        href="#engagements"
                        class="transition hover:text-yellow-400"
                    >
                        Services
                    </a>

                    <a
                        href="#engagements"
                        class="transition hover:text-yellow-400"
                    >
                        Pulse Check
                    </a>

                    <a
                        href="#faq"
                        class="transition hover:text-yellow-400"
                    >
                        FAQ
                    </a>

                </div>

            </div>

            {{-- Contact --}}
            <div>

                <h3 class="font-semibold text-white">
                    Contact
                </h3>

                <div class="mt-4 flex flex-col gap-3 text-sm">

                    <a
                        href="https://yellomind.com"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="transition hover:text-yellow-400"
                    >
                        Yellomind Consulting
                    </a>

                    <a
                        href="mailto:info@yellomind.com"
                        class="text-slate-400 transition hover:text-yellow-400"
                    >
                        info@yellomind.com
                    </a>

                    <a
                        href="mailto:info@yellomind.com?subject=YARA%20Assessment%20Enquiry"
                        class="text-slate-400 transition hover:text-yellow-400"
                    >
                        Speak to Yellomind
                    </a>

                </div>

            </div>

        </div>

        <div class="mt-12 flex flex-col justify-between gap-4 border-t border-slate-800 pt-6 text-sm text-slate-500 md:flex-row">

            <p>
                © {{ date('Y') }} YARA Platform. All rights reserved.
            </p>

            <div class="flex gap-6">

                <a
                    href="#"
                    class="transition hover:text-yellow-400"
                >
                    Privacy Policy
                </a>

                <a
                    href="#"
                    class="transition hover:text-yellow-400"
                >
                    Terms & Conditions
                </a>

            </div>

        </div>

    </div>

</footer>

</body>

</html>