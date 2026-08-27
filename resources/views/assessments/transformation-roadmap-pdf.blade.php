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