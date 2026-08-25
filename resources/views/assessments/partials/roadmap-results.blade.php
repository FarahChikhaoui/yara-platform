  {{-- Generated roadmap actions --}}
                @if($roadmapActionCount)

                    @foreach($roadmapActions as $action)
                        @php
                            $impactClass = match(strtolower($action['business_impact'] ?? '')) {
                                'critical' => 'bg-red-100 text-red-700',
                                'high' => 'bg-orange-100 text-orange-700',
                                'medium' => 'bg-yellow-100 text-yellow-700',
                                default => 'bg-green-100 text-green-700',
                            };
                        @endphp

                        <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

                                <div class="min-w-0 flex-1">

                                    <div class="flex flex-wrap items-center gap-3">

                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-950 font-bold text-white">
                                            {{ $action['priority_rank'] ?? $loop->iteration }}
                                        </span>

                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                Priority initiative
                                                @if(!empty($action['dimension']))
                                                    · {{ $action['dimension'] }}
                                                @endif
                                            </p>

                                            <h3 class="mt-1 text-xl font-bold text-slate-950">
                                                {{ $action['title'] ?? 'Untitled action' }}
                                            </h3>
                                        </div>

                                    </div>

                                    <p class="mt-5 max-w-4xl leading-7 text-slate-600">
                                        {{ $action['description'] ?? '' }}
                                    </p>

                                    @if(!empty($action['business_rationale']))
                                        <div class="mt-5 rounded-xl border border-yellow-200 bg-yellow-50 p-4">
                                            <p class="text-sm font-bold text-slate-900">
                                                Why this matters
                                            </p>

                                            <p class="mt-2 leading-6 text-slate-700">
                                                {{ $action['business_rationale'] }}
                                            </p>
                                        </div>
                                    @endif

                                </div>

                                @if(!empty($action['business_impact']))
                                    <span class="inline-flex w-fit rounded-full px-3 py-1 text-sm font-bold {{ $impactClass }}">
                                        {{ $action['business_impact'] }} impact
                                    </span>
                                @endif

                            </div>

                            <div class="mt-6 grid grid-cols-1 gap-4 border-t border-slate-200 pt-6 sm:grid-cols-2 xl:grid-cols-4">

                                <div class="rounded-xl bg-white p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Effort
                                    </p>

                                    <p class="mt-2 font-bold text-slate-950">
                                        {{ $action['effort'] ?? 'Not specified' }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-white p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Timeline
                                    </p>

                                    <p class="mt-2 font-bold text-slate-950">
                                        {{ $action['timeline'] ?? 'Not specified' }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-white p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Estimated investment
                                    </p>

                                    <p class="mt-2 font-bold text-slate-950">
                                        {{ $action['investment'] ?? 'Not specified' }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-white p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Standard reference
                                    </p>

                                    <p class="mt-2 font-bold text-slate-950">
                                        {{ $action['standard_reference'] ?? 'Not specified' }}
                                    </p>
                                </div>

                            </div>

                        </article>

                    @endforeach

                @elseif($roadmapData)

                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-8">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-amber-500 text-xl font-bold text-white">
                                !
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-2xl font-bold text-slate-900">
                                    The AI didn't return a usable roadmap
                                </h3>
                                <p class="mt-3 max-w-4xl leading-7 text-slate-600">
                                    Try regenerating — if this keeps happening, adjust your inputs above or reach out to support.
                                </p>
                            </div>
                        </div>
                    </div>

                @endif