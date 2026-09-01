<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>YARA Transformation Roadmap</title>

    <style>
        @page {
            margin: 32px 38px 45px 38px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 10px;
            line-height: 1.55;
        }

        .header {
            border-bottom: 4px solid #facc15;
            padding-bottom: 18px;
            margin-bottom: 28px;
        }

        .brand {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .brand-subtitle {
            margin-top: 3px;
            color: #64748b;
            font-size: 9px;
        }

        h1 {
            margin: 20px 0 5px;
            font-size: 25px;
            line-height: 1.2;
        }

        .subtitle {
            color: #64748b;
            font-size: 11px;
        }

        .meta {
            width: 100%;
            margin-top: 22px;
            border-collapse: collapse;
        }

        .meta td {
            width: 25%;
            border: 1px solid #e2e8f0;
            padding: 10px;
            vertical-align: top;
        }

        .meta-label {
            display: block;
            margin-bottom: 4px;
            color: #64748b;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .meta-value {
            font-weight: bold;
            font-size: 10px;
        }

        .status {
            color: #166534;
        }

        .section {
            margin-top: 28px;
        }

        .eyebrow {
            margin-bottom: 4px;
            color: #a16207;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        h2 {
            margin: 0 0 6px;
            font-size: 18px;
        }

        .section-description {
            margin: 0 0 18px;
            color: #64748b;
        }

        .initiative {
            margin-bottom: 18px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            page-break-inside: avoid;
        }

        .initiative-header {
            padding: 13px 15px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .initiative-number {
            color: #a16207;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .7px;
        }

        .initiative-title {
            margin-top: 4px;
            font-size: 14px;
            font-weight: bold;
        }

        .initiative-body {
            padding: 14px 15px;
        }

        .label {
            margin-top: 11px;
            margin-bottom: 3px;
            color: #475569;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .label:first-child {
            margin-top: 0;
        }

        .value {
            margin: 0;
        }

        .list {
            margin: 5px 0 0;
            padding-left: 17px;
        }

        .list li {
            margin-bottom: 3px;
        }

        .attributes {
            width: 100%;
            margin-top: 14px;
            border-collapse: collapse;
        }

        .attributes td {
            width: 25%;
            padding: 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .guidance {
            margin-top: 12px;
            padding: 12px;
            background: #fffbeb;
            border-left: 3px solid #facc15;
        }

        .guidance-title {
            margin-bottom: 4px;
            font-weight: bold;
        }

        .expert-section {
            margin-top: 28px;
            page-break-inside: avoid;
        }

        .expert-box {
            margin-top: 10px;
            padding: 14px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .expert-box-title {
            margin-bottom: 6px;
            font-size: 11px;
            font-weight: bold;
        }
        .snapshot {
            width: 100%;
            margin-top: 12px;
            border-collapse: collapse;
        }

        .snapshot td {
            width: 25%;
            padding: 12px 10px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .snapshot-value {
            display: block;
            margin-top: 4px;
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
        }

        .brief-headline {
            margin-top: 12px;
            padding: 14px 16px;
            background: #fffbeb;
            border-left: 3px solid #facc15;
            font-size: 11px;
            font-weight: bold;
            line-height: 1.6;
        }

        .brief-table {
            width: 100%;
            margin-top: 12px;
            border-collapse: separate;
            border-spacing: 8px 0;
        }

        .brief-table td {
            width: 33.33%;
            padding: 13px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            vertical-align: top;
        }

        .brief-type {
            margin-bottom: 5px;
            color: #a16207;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .6px;
        }

        .brief-title {
            margin-bottom: 5px;
            font-size: 11px;
            font-weight: bold;
        }

        .brief-dimension {
            margin-bottom: 7px;
            color: #64748b;
            font-size: 8px;
        }

        .objectives {
            width: 100%;
            margin-top: 12px;
            border-collapse: collapse;
        }

        .objectives td {
            width: 33.33%;
            padding: 12px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
/* =========================================================
   TRANSFORMATION TIMELINE
========================================================= */

.timeline-section {
    margin-top: 30px;
}

.timeline-table {
    width: 100%;
    margin-top: 14px;
    border-collapse: collapse;
    table-layout: fixed;
    font-size: 8px;
}

.timeline-table th {
    padding: 7px 3px;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    color: #475569;
    font-size: 7px;
    font-weight: bold;
    text-align: center;
}

.timeline-table th.timeline-initiative-header {
    width: 28%;
    padding-left: 8px;
    text-align: left;
}

.timeline-table td {
    height: 34px;
    padding: 0;
    border: 1px solid #e2e8f0;
    vertical-align: middle;
}

.timeline-table td.timeline-name {
    padding: 7px 8px;
    background: #ffffff;
}

.timeline-name-title {
    font-size: 8px;
    font-weight: bold;
    line-height: 1.3;
}

.timeline-name-phase {
    margin-top: 2px;
    color: #94a3b8;
    font-size: 7px;
}

.timeline-cell {
    padding: 0;
}

.timeline-bar {
    height: 16px;
    width: 100%;
}

.timeline-bar-high {
    background: #f87171;
}

.timeline-bar-medium {
    background: #fbbf24;
}

.timeline-bar-low {
    background: #34d399;
}

.timeline-bar-default {
    background: #facc15;
}

.timeline-legend {
    margin-top: 8px;
    color: #64748b;
    font-size: 7px;
}

.timeline-legend-item {
    margin-right: 14px;
}

.timeline-dot {
    display: inline-block;
    width: 6px;
    height: 6px;
    margin-right: 4px;
}

        .footer {
            margin-top: 30px;
            padding-top: 12px;
            border-top: 1px solid #cbd5e1;
            color: #64748b;
            font-size: 8px;
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- DOCUMENT HEADER --}}
    <div class="header">

        <div class="brand">
            YARA
        </div>

        <div class="brand-subtitle">
            Yellomind · AI Readiness & Transformation
        </div>

        <h1>
            Transformation Roadmap
        </h1>

        <div class="subtitle">
            Expert-reviewed implementation plan for
            {{ $assessment->company?->name ?? 'your organization' }}.
        </div>

        <table class="meta">
            <tr>
                <td>
                    <span class="meta-label">
                        Organization
                    </span>

                    <span class="meta-value">
                        {{ $assessment->company?->name ?? '—' }}
                    </span>
                </td>

                <td>
                    <span class="meta-label">
                        Status
                    </span>

                    <span class="meta-value status">
                        Expert Reviewed
                    </span>
                </td>

                <td>
                    <span class="meta-label">
                        Finalized
                    </span>

                    <span class="meta-value">
                        {{ $roadmap->finalized_at
                            ? $roadmap->finalized_at->format('d M Y')
                            : '—'
                        }}
                    </span>
                </td>

                <td>
                    <span class="meta-label">
                        Initiatives
                    </span>

                    <span class="meta-value">
                        {{ $roadmap->initiatives->count() }}
                    </span>
                </td>
            </tr>
        </table>

    </div>

{{-- =====================================================
     AI READINESS SNAPSHOT
====================================================== --}}
<div class="section">

    <div class="eyebrow">
        Assessment Context
    </div>

    <h2>
        AI Readiness Snapshot
    </h2>

    <p class="section-description">
        A concise view of the organizational readiness evidence
        used to inform this Transformation Roadmap.
    </p>

    <table class="snapshot">
        <tr>

            <td>
                <span class="meta-label">
                    Organizational Readiness
                </span>

                <span class="snapshot-value">
                    {{ number_format((float) $assessment->company_score, 1) }}/100
                </span>
            </td>

            <td>
                <span class="meta-label">
                    Current Maturity
                </span>

                <span class="snapshot-value">
                    @if($currentMaturity)
                        Level {{ $currentMaturity->level }}
                    @else
                        —
                    @endif
                </span>

                @if($currentMaturity)
                    <div style="margin-top:3px; color:#64748b; font-size:8px;">
                        {{ $currentMaturity->label ?? $currentMaturity->name ?? '' }}
                    </div>
                @endif
            </td>

            <td>
                <span class="meta-label">
                    Country Benchmark
                </span>

                <span class="snapshot-value">
                    @if($assessment->country_ai_score !== null)
                        {{ number_format((float) $assessment->country_ai_score, 1) }}/100
                    @else
                        —
                    @endif
                </span>

                @if($assessment->country_ai_year)
                    <div style="margin-top:3px; color:#64748b; font-size:8px;">
                        {{ $assessment->country_ai_year }} benchmark
                    </div>
                @endif
            </td>

            <td>
                <span class="meta-label">
                    YARA Composite
                </span>

                <span class="snapshot-value">
                    @if($assessment->combined_score !== null)
                        {{ number_format((float) $assessment->combined_score, 1) }}/100
                    @else
                        —
                    @endif
                </span>
            </td>

        </tr>
    </table>

</div>


{{-- =====================================================
     AI ASSESSMENT BRIEF
====================================================== --}}
@if($aiBrief)

    <div class="section">

        <div class="eyebrow">
            Expert Decision Support
        </div>

        <h2>
            AI Assessment Brief
        </h2>

        <p class="section-description">
            AI-assisted interpretation of the assessment evidence
            used to support expert review and roadmap development.
        </p>

        @if(!empty($aiBrief['headline']))
            <div class="brief-headline">
                {{ $aiBrief['headline'] }}
            </div>
        @endif


        <table class="brief-table">
            <tr>

                {{-- PRIORITY RISK --}}
                <td>
                    <div class="brief-type">
                        Priority Risk
                    </div>

                    <div class="brief-title">
                        {{ $aiBrief['priority_risk']['title'] ?? '—' }}
                    </div>

                    @if(!empty($aiBrief['priority_risk']['dimension']))
                        <div class="brief-dimension">
                            {{ $aiBrief['priority_risk']['dimension'] }}
                        </div>
                    @endif

                    <div>
                        {{ $aiBrief['priority_risk']['insight'] ?? '—' }}
                    </div>
                </td>


                {{-- STRATEGIC STRENGTH --}}
                <td>
                    <div class="brief-type">
                        Strategic Strength
                    </div>

                    <div class="brief-title">
                        {{ $aiBrief['strategic_strength']['title'] ?? '—' }}
                    </div>

                    @if(!empty($aiBrief['strategic_strength']['dimension']))
                        <div class="brief-dimension">
                            {{ $aiBrief['strategic_strength']['dimension'] }}
                        </div>
                    @endif

                    <div>
                        {{ $aiBrief['strategic_strength']['insight'] ?? '—' }}
                    </div>
                </td>


                {{-- READINESS PATTERN --}}
                <td>
                    <div class="brief-type">
                        Readiness Pattern
                    </div>

                    <div class="brief-title">
                        {{ $aiBrief['readiness_pattern']['title'] ?? '—' }}
                    </div>

                    <div>
                        {{ $aiBrief['readiness_pattern']['insight'] ?? '—' }}
                    </div>
                </td>

            </tr>
        </table>

    </div>

@endif


{{-- =====================================================
     TRANSFORMATION OBJECTIVES
====================================================== --}}
@if($assessment->roadmapPreference)

    <div class="section">

        <div class="eyebrow">
            Transformation Direction
        </div>

        <h2>
            Transformation Objectives
        </h2>

        <p class="section-description">
            The target and delivery constraints defined by the
            organization before roadmap development.
        </p>

        <table class="objectives">
            <tr>

                <td>
                    <span class="meta-label">
                        Maturity Direction
                    </span>

                    <span class="meta-value">
                        @if($currentMaturity)
                            Level {{ $currentMaturity->level }}
                        @else
                            —
                        @endif

                        →

                        {{ $assessment->roadmapPreference->target_maturity ?? '—' }}
                    </span>
                </td>

                <td>
                    <span class="meta-label">
                        Timeline
                    </span>

                    <span class="meta-value">
                        {{ $assessment->roadmapPreference->timeframe ?? '—' }}
                    </span>
                </td>

                <td>
                    <span class="meta-label">
                        Investment Capacity
                    </span>

                    <span class="meta-value">
                        {{ $assessment->roadmapPreference->budget_level ?? '—' }}
                    </span>
                </td>

            </tr>
        </table>

    </div>

@endif
    {{-- INTRODUCTION --}}
    <div class="section">

        <div class="eyebrow">
            Expert-Reviewed Transformation Plan
        </div>

        <h2>
            Prioritized Initiatives
        </h2>

        <p class="section-description">
            This roadmap translates your organization's AI readiness
            assessment into a prioritized implementation plan reviewed
            and validated by a YARA expert.
        </p>

    </div>


    {{-- INITIATIVES --}}
    @foreach($roadmap->initiatives as $initiative)

        <div class="initiative">

            <div class="initiative-header">

                <div class="initiative-number">
                    Initiative {{ $loop->iteration }}

                    @if($initiative->priority)
    · {{ ucfirst($initiative->priority) }} Priority
@endif
                </div>

                <div class="initiative-title">
                    {{ $initiative->title }}
                </div>

            </div>


            <div class="initiative-body">

                @if($initiative->description)
                    <div class="label">
                        Initiative
                    </div>

                    <p class="value">
                        {{ $initiative->description }}
                    </p>
                @endif


                @if($initiative->business_rationale)
                    <div class="label">
                        Why this matters
                    </div>

                    <p class="value">
                        {{ $initiative->business_rationale }}
                    </p>
                @endif


                @if($initiative->expected_outcome)
                    <div class="label">
                        Expected outcome
                    </div>

                    <p class="value">
                        {{ $initiative->expected_outcome }}
                    </p>
                @endif


                @if(!empty($initiative->recommended_actions))
                    <div class="label">
                        Recommended actions
                    </div>

                    <ul class="list">
                        @foreach($initiative->recommended_actions as $action)
                            <li>{{ $action }}</li>
                        @endforeach
                    </ul>
                @endif


                @if(!empty($initiative->success_metrics))
                    <div class="label">
                        Success metrics
                    </div>

                    <ul class="list">
                        @foreach($initiative->success_metrics as $metric)
                            <li>{{ $metric }}</li>
                        @endforeach
                    </ul>
                @endif


                <table class="attributes">
                    <tr>

                        <td>
                            <span class="meta-label">
                                Phase
                            </span>

                            <span class="meta-value">
{{ $initiative->phase ? ucfirst($initiative->phase) : '—' }}                            </span>
                        </td>

                        <td>
                            <span class="meta-label">
                                Investment
                            </span>

                            <span class="meta-value">
                               {{ $initiative->investment ? ucfirst($initiative->investment) : '—' }}
                            </span>
                        </td>

                        <td>
                            <span class="meta-label">
                                Effort
                            </span>

                            <span class="meta-value">
{{ $initiative->effort ? ucfirst($initiative->effort) : '—' }}                            </span>
                        </td>

                        <td>
                            <span class="meta-label">
                                Impact
                            </span>

                            <span class="meta-value">
{{ $initiative->impact ? ucfirst($initiative->impact) : '—' }}                            </span>
                        </td>

                    </tr>
                </table>


                @if(!empty($initiative->dependencies))
                    <div class="label">
                        Dependencies
                    </div>

                    <ul class="list">
                        @foreach($initiative->dependencies as $dependency)
                            <li>{{ $dependency }}</li>
                        @endforeach
                    </ul>
                @endif


                @if($initiative->standard_reference)
                    <div class="label">
                        Framework reference
                    </div>

                    <p class="value">
                        {{ $initiative->standard_reference }}
                    </p>
                @endif


                @if($initiative->consultant_guidance)
                    <div class="guidance">

                        <div class="guidance-title">
                            Expert Guidance
                        </div>

                        {{ $initiative->consultant_guidance }}

                    </div>
                @endif

            </div>

        </div>

    @endforeach

{{-- =====================================================
     TRANSFORMATION TIMELINE
====================================================== --}}

@php
    $pdfTimeframe = $assessment->roadmapPreference?->timeframe;

    $pdfTimelineMonths = match ($pdfTimeframe) {
        '3 months' => 3,
        '6 months' => 6,
        '12 months' => 12,
        '18-24 months' => 24,
        default => null,
    };

    $pdfTimelineInitiatives = $roadmap->initiatives
        ->filter(
            fn ($initiative) =>
                $initiative->start_month !== null
                && $initiative->duration_months !== null
        );
@endphp


@if($pdfTimelineMonths && $pdfTimelineInitiatives->isNotEmpty())

    <div class="timeline-section">

        <div class="eyebrow">
            Transformation Timeline
        </div>

        <h2>
            Implementation Roadmap
        </h2>

        <p class="section-description">
            Planned sequencing of initiatives across the
            {{ $pdfTimeframe }} transformation horizon.
        </p>


        <table class="timeline-table">

            <thead>
                <tr>

                    <th class="timeline-initiative-header">
                        Initiative
                    </th>

                    @for($month = 1; $month <= $pdfTimelineMonths; $month++)
                        <th>
                            M{{ $month }}
                        </th>
                    @endfor

                </tr>
            </thead>


            <tbody>

                @foreach($pdfTimelineInitiatives as $initiative)

                    @php
                        $startMonth = max(
                            1,
                            (int) $initiative->start_month
                        );

                        $durationMonths = max(
                            1,
                            (int) $initiative->duration_months
                        );

                        $endMonth = min(
                            $pdfTimelineMonths,
                            $startMonth + $durationMonths - 1
                        );

                        $pdfPriorityClass = match(
                            strtolower($initiative->priority ?? '')
                        ) {
                            'high' => 'timeline-bar-high',
                            'medium' => 'timeline-bar-medium',
                            'low' => 'timeline-bar-low',
                            default => 'timeline-bar-default',
                        };
                    @endphp


                    <tr>

                        {{-- Initiative name --}}
                        <td class="timeline-name">

                            <div class="timeline-name-title">
                                {{ $initiative->title }}
                            </div>

                            <div class="timeline-name-phase">
                                {{ $initiative->phase
                                    ?? ('Months ' . $startMonth . '-' . $endMonth) }}
                            </div>

                        </td>


                        {{-- Timeline months --}}
                        @for($month = 1; $month <= $pdfTimelineMonths; $month++)

                            @php
                                $isActive =
                                    $month >= $startMonth
                                    && $month <= $endMonth;
                            @endphp

                            <td class="timeline-cell">

                                @if($isActive)
                                    <div class="timeline-bar {{ $pdfPriorityClass }}"></div>
                                @endif

                            </td>

                        @endfor

                    </tr>

                @endforeach

            </tbody>

        </table>


        {{-- Priority legend --}}
        <div class="timeline-legend">

            <span class="timeline-legend-item">
                <span
                    class="timeline-dot"
                    style="background:#f87171;"
                ></span>
                High priority
            </span>

            <span class="timeline-legend-item">
                <span
                    class="timeline-dot"
                    style="background:#fbbf24;"
                ></span>
                Medium priority
            </span>

            <span class="timeline-legend-item">
                <span
                    class="timeline-dot"
                    style="background:#34d399;"
                ></span>
                Low priority
            </span>

        </div>

    </div>

@endif

    {{-- OVERALL EXPERT GUIDANCE --}}
    @if(
        $roadmap->consultant_notes ||
        $roadmap->risks_dependencies
    )

        <div class="expert-section">

            <div class="eyebrow">
                Expert Guidance
            </div>

            <h2>
                Implementation Guidance
            </h2>


            @if($roadmap->consultant_notes)

                <div class="expert-box">

                    <div class="expert-box-title">
                        Implementation Guidance
                    </div>

                    {{ $roadmap->consultant_notes }}

                </div>

            @endif


            @if($roadmap->risks_dependencies)

                <div class="expert-box">

                    <div class="expert-box-title">
                        Risks & Dependencies
                    </div>

                    {{ $roadmap->risks_dependencies }}

                </div>

            @endif

        </div>

    @endif


    {{-- DOCUMENT FOOTER --}}
    <div class="footer">
        YARA · Yellomind · Expert-Reviewed AI Transformation Roadmap
        <br>
        Generated {{ now()->format('d M Y') }}
    </div>

</body>
</html>