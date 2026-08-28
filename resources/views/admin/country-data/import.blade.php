@extends('layouts.admin')

@section('content')

<div class="relative mx-auto max-w-5xl space-y-8">

    {{-- Ambient background accent --}}
    <div class="pointer-events-none fixed -top-24 left-1/2 -z-10 h-72 w-[36rem] -translate-x-1/2 rounded-full bg-amber-300/10 blur-3xl"></div>

    {{-- Floating Back Button --}}
<a
    href="{{ route('admin.country-data.index') }}"
    title="Back to Country Data"
    class="group fixed left-[360px] top-1/2 z-40
           flex h-11 w-11 -translate-y-1/2
           items-center justify-center
           rounded-full border border-slate-200
           bg-white text-slate-600
           shadow-lg transition-all duration-200
           hover:-translate-x-1
           hover:bg-slate-950 hover:text-white
           hover:shadow-xl"
>
    <svg
        class="h-5 w-5"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M15 19l-7-7 7-7"
        />
    </svg>
</a>


    {{-- Header --}}
    <div>

        <p class="flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.16em] text-amber-600">
            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
            Benchmark Data
        </p>

        <h1 class="mt-2 text-4xl font-bold tracking-tight text-slate-950">
            Import Benchmark Dataset
        </h1>

        <p class="mt-3 max-w-3xl text-lg leading-7 text-slate-500">
            Import a processed country AI readiness dataset generated
            by YARA's Python ETL pipeline.
        </p>

    </div>


    {{-- Current benchmark --}}
    @if($activeDataset)

        <div
            class="relative flex items-center justify-between gap-5
                   overflow-hidden rounded-2xl border border-emerald-200/70
                   bg-gradient-to-r from-emerald-50 to-emerald-50/40 px-6 py-4
                   shadow-[0_1px_2px_rgba(15,23,42,0.03),0_16px_32px_-26px_rgba(16,185,129,0.35)]"
        >

            <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-emerald-300/20 blur-2xl"></div>

            <div class="relative flex items-center gap-3">

                <span class="relative flex h-2.5 w-2.5">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60"></span>
                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                </span>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">
                        Current Benchmark
                    </p>

                    <p class="mt-0.5 font-bold text-emerald-950">
                        {{ $activeDataset->year }}
                        ·
                        {{ $activeDataset->source }}
                    </p>

                </div>

            </div>

            <span
                class="relative rounded-full bg-emerald-100
                       px-3 py-1 text-xs font-semibold
                       text-emerald-700 ring-1 ring-inset ring-emerald-200/70"
            >
                Active
            </span>

        </div>

    @endif

  {{-- Import form --}}
<form
    method="POST"
    action="{{ route('admin.country-data.import.store') }}"
    enctype="multipart/form-data"
    class="overflow-hidden rounded-2xl border border-slate-200/70 bg-white shadow-[0_1px_2px_rgba(15,23,42,0.04),0_24px_48px_-30px_rgba(15,23,42,0.3)]"
>
    @csrf

    {{-- Errors --}}
    @if($errors->any())
        <div class="border-b border-red-200 bg-gradient-to-r from-red-50 to-rose-50 px-7 py-4">
            <p class="flex items-center gap-2 text-sm font-semibold text-red-800">
                <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.8 2.5 17.3A2 2 0 0 0 4.2 20h15.6a2 2 0 0 0 1.7-2.7L13.7 3.8a2 2 0 0 0-3.4 0Z"/></svg>
                Dataset could not be imported
            </p>

            <ul class="mt-1 space-y-1 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="p-7">

        {{-- Metadata --}}
        <div class="grid gap-6 md:grid-cols-2">

            <div>
                <label
                    for="year"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Dataset Year
                </label>

                <input
                    id="year"
                    type="number"
                    name="year"
                    value="{{ old('year') }}"
                    min="2019"
                    max="{{ now()->year + 1 }}"
                    placeholder="2026"
                    required
                    class="w-full rounded-xl border border-slate-300
                           bg-white px-4 py-3 text-sm text-slate-900
                           shadow-sm outline-none transition
                           focus:border-slate-500 focus:ring-2
                           focus:ring-slate-100"
                >
            </div>


            <div>
                <label
                    for="source"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Data Source
                </label>

                <input
                    id="source"
                    type="text"
                    name="source"
                    value="{{ old('source', 'Oxford Insights') }}"
                    required
                    class="w-full rounded-xl border border-slate-300
                           bg-white px-4 py-3 text-sm text-slate-900
                           shadow-sm outline-none transition
                           focus:border-slate-500 focus:ring-2
                           focus:ring-slate-100"
                >
            </div>

        </div>


        {{-- Upload --}}
        <div class="mt-7">

            <label class="mb-2 block text-sm font-semibold text-slate-700">
                Processed Dataset
            </label>


            <div
                id="upload-zone"
                class="relative flex min-h-[220px] flex-col
                       items-center justify-center overflow-hidden rounded-2xl
                       border-2 border-dashed border-slate-300
                       bg-slate-50/70 px-6 py-10 text-center
                       transition-all duration-200
                       hover:border-amber-300 hover:bg-amber-50/30"
            >

                <div class="pointer-events-none absolute inset-0 opacity-[0.5]" style="background-image: radial-gradient(circle at 1px 1px, rgba(15,23,42,0.05) 1px, transparent 0); background-size: 22px 22px;"></div>

                {{-- Upload icon --}}
                <div
                    class="relative flex h-14 w-14 items-center justify-center
                           rounded-2xl border border-slate-200
                           bg-white shadow-sm"
                >
                    <svg
                        class="h-6 w-6 text-slate-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 16V4m0 0L8 8m4-4 4 4
                               M5 14v5a2 2 0 002 2h10
                               a2 2 0 002-2v-5"
                        />
                    </svg>
                </div>


                <p
                    id="upload-title"
                    class="relative mt-5 text-sm font-semibold text-slate-900"
                >
                    Upload processed benchmark dataset
                </p>

                <p
                    id="upload-description"
                    class="relative mt-1 text-sm text-slate-500"
                >
                    Select the CSV generated by your Python ETL pipeline.
                </p>


                {{-- Trigger only --}}
                <button
                    type="button"
                    id="choose-dataset-button"
                    class="relative mt-5 rounded-lg bg-gradient-to-r from-slate-950 to-slate-800
                           px-4 py-2.5 text-sm font-semibold
                           text-white shadow-md shadow-slate-900/20 transition-all duration-200
                           hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/25"
                >
                    Choose CSV file
                </button>


                <p class="relative mt-3 text-xs text-slate-400">
                    CSV only · Maximum 10 MB
                </p>


                {{--
                    Real input.
                    It belongs to the form, but the BUTTON opens it.
                    We are NOT wrapping the whole upload area in a label.
                --}}
                <input
                    id="dataset_file"
                    type="file"
                    name="dataset_file"
                    accept=".csv,text/csv"
                    required
                    class="absolute h-px w-px opacity-0"
                >

            </div>

        </div>


        {{-- Activation --}}
        <div
            class="mt-6 flex items-start gap-3 rounded-xl
                   border border-slate-200 bg-slate-50
                   px-5 py-4 transition hover:border-slate-300"
        >
            <input
                id="activate"
                type="checkbox"
                name="activate"
                value="1"
                {{ old('activate') ? 'checked' : '' }}
                class="mt-1 h-4 w-4 rounded border-slate-300
                       text-slate-950 focus:ring-slate-400"
            >

            <div>
                <label
                    for="activate"
                    class="cursor-pointer text-sm font-semibold text-slate-800"
                >
                    Set as active benchmark after import
                </label>

                <p class="mt-1 text-xs leading-5 text-slate-500">
                    New assessments will use this release for country benchmarking.
                    Existing completed assessments remain unchanged.
                </p>
            </div>

        </div>

    </div>


    {{-- Footer --}}
    <div
        class="flex items-center justify-between
               border-t border-slate-200 bg-slate-50/60
               px-7 py-5"
    >

        <p class="text-xs text-slate-400">
            Existing benchmark years cannot be overwritten.
        </p>


        <div class="flex items-center gap-3">

            <a
                href="{{ route('admin.country-data.index') }}"
                class="rounded-lg border border-slate-300
                       bg-white px-4 py-2.5 text-sm
                       font-semibold text-slate-700 shadow-sm
                       transition hover:bg-slate-100"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-lg bg-gradient-to-r from-slate-950 to-slate-800
                       px-5 py-2.5 text-sm font-semibold
                       text-white shadow-md shadow-slate-900/20 transition-all duration-200
                       hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/25"
            >
                Import dataset
            </button>

        </div>

    </div>

</form>


{{-- File picker behaviour --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const fileInput = document.getElementById('dataset_file');
    const chooseButton = document.getElementById('choose-dataset-button');

    const title = document.getElementById('upload-title');
    const description = document.getElementById('upload-description');
    const uploadZone = document.getElementById('upload-zone');


    chooseButton.addEventListener('click', function () {
        fileInput.click();
    });


    fileInput.addEventListener('change', function () {

        if (!fileInput.files.length) {
            return;
        }

        const file = fileInput.files[0];

        title.textContent = file.name;

        description.textContent =
            `${(file.size / 1024).toFixed(1)} KB · Ready to import`;

        uploadZone.classList.remove(
            'border-slate-300',
            'bg-slate-50/70'
        );

        uploadZone.classList.add(
            'border-emerald-300',
            'bg-emerald-50/40'
        );

        chooseButton.textContent = 'Choose another file';

    });

});
</script>

    
@endsection
