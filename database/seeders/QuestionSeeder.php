<?php

namespace Database\Seeders;

use App\Models\Dimension;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('answer_options')->truncate();
        DB::table('questions')->truncate();

        Schema::enableForeignKeyConstraints();

        $questions = [

            // =========================================================
            // D1 — AI Strategy & Vision
            // =========================================================
            [
                'dimension_code' => 'D1',
                'question_text' => "What is the status of your organization's AI strategy?",
                'order' => 1,
                'weight' => 1,
                'include_in_pulse' => true,
                'pulse_order' => null,
                'help_text' => 'Consider whether your AI strategy is formally documented and approved, how closely it is connected to the overall business strategy, whether funding has been committed, and whether leadership reviews it regularly.',
                'is_active' => true,
                'options' => [
                    ['label' => 'No AI strategy exists and none is planned.', 'score' => 1, 'order' => 1],
                    ['label' => 'AI strategy discussions have occurred at senior level but nothing is documented or approved.', 'score' => 2, 'order' => 2],
                    ['label' => 'An AI strategy document exists and has been approved by senior leadership.', 'score' => 3, 'order' => 3],
                    ['label' => 'A board-approved AI strategy exists, is linked to the business strategy, has a funded multi-year roadmap, and is subject to regular executive review.', 'score' => 4, 'order' => 4],
                ],
            ],
            [
                'dimension_code' => 'D1',
                'question_text' => 'How is AI investment prioritized within the organization?',
                'order' => 2,
                'weight' => 1,
                'include_in_pulse' => true,
                'pulse_order' => null,
                'help_text' => 'Think about how your organization decides which AI initiatives receive funding and resources. Consider whether decisions are informal or based on documented criteria such as business value, feasibility and risk.',
                'is_active' => true,
                'options' => [
    [
        'label' => 'AI investments are made ad hoc, without defined prioritization criteria.',
        'score' => 1,
        'order' => 1
    ],
    [
        'label' => 'AI initiatives are prioritized mainly through individual business sponsorship or available funding, with limited consistency.',
        'score' => 2,
        'order' => 2
    ],
    [
        'label' => 'AI investments are prioritized using documented criteria such as business value, feasibility, strategic alignment and risk.',
        'score' => 3,
        'order' => 3
    ],
    [
        'label' => 'AI investment is managed as a strategic portfolio, using standardized prioritization criteria, executive governance and regular portfolio reviews.',
        'score' => 4,
        'order' => 4
    ],
],
            ],
            [
                'dimension_code' => 'D1',
                'question_text' => 'How does your organization monitor the external AI landscape?',
                'order' => 3,
                'weight' => 1,
                'include_in_pulse' => false,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'No systematic monitoring occurs.', 'score' => 1, 'order' => 1],
                    ['label' => 'Individuals track developments informally.', 'score' => 2, 'order' => 2],
                    ['label' => 'A defined process exists for monitoring relevant AI developments.', 'score' => 3, 'order' => 3],
                    ['label' => 'A structured AI intelligence function provides regular board-level briefings and maps implications to the AI roadmap.', 'score' => 4, 'order' => 4],
                ],
            ],

            // =========================================================
            // D2 — Data Readiness
            // =========================================================
            [
                'dimension_code' => 'D2',
                'question_text' => 'How would you characterize the quality of data available for AI use cases?',
                'order' => 4,
                'weight' => 1,
                'include_in_pulse' => true,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'Data quality is unknown; no systematic assessment has been performed.', 'score' => 1, 'order' => 1],
                    ['label' => "Known data-quality issues exist and are documented, but remediation hasn't started.", 'score' => 2, 'order' => 2],
                    ['label' => 'Data-quality processes exist; important AI datasets have been profiled and remediation is underway.', 'score' => 3, 'order' => 3],
                    ['label' => 'Continuous data-quality monitoring operates across AI-relevant data domains, with KPIs tracked and reported.', 'score' => 4, 'order' => 4],
                ],
            ],
            [
                'dimension_code' => 'D2',
                'question_text' => 'Does the organization maintain a data catalogue covering AI-relevant datasets?',
                'order' => 5,
                'weight' => 1,
                'include_in_pulse' => true,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'No data catalogue exists.', 'score' => 1, 'order' => 1],
                    ['label' => 'Partial inventories exist in silos.', 'score' => 2, 'order' => 2],
                    ['label' => 'An enterprise catalogue covers most AI-relevant datasets with metadata, ownership and classification.', 'score' => 3, 'order' => 3],
                    ['label' => 'The catalogue is comprehensive and actively maintained, integrated with governance, and includes AI-specific metadata such as provenance, bias flags, consent status and training-data lineage.', 'score' => 4, 'order' => 4],
                ],
            ],
            [
                'dimension_code' => 'D2',
                'question_text' => 'How is personal data managed in compliance with applicable regulations?',
                'order' => 5,
                'weight' => 1,
                'include_in_pulse' => false,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'No formal personal-data management programme exists.', 'score' => 1, 'order' => 1],
                    ['label' => "Basic policies exist but aren't operationally enforced across AI use cases.", 'score' => 2, 'order' => 2],
                    ['label' => 'An operational data-protection programme covers most AI-relevant data flows.', 'score' => 3, 'order' => 3],
                    ['label' => 'All AI data flows are covered, with privacy-by-design, regular compliance audits and documented data-subject rights management.', 'score' => 4, 'order' => 4],
                ],
            ],

            // =========================================================
            // D3 — Technology Infrastructure & MLOps
            // =========================================================
            [
                'dimension_code' => 'D3',
                'question_text' => "How capable is the organization's technology infrastructure of supporting AI workloads?",
                'order' => 1,
                'weight' => 1,
                'include_in_pulse' => false,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'No dedicated or suitable infrastructure exists for AI workloads; experimentation depends on individual or temporary environments.', 'score' => 1, 'order' => 1],
                    ['label' => 'Some AI-capable infrastructure is available, but it is provisioned manually and used mainly for isolated experiments or pilots.', 'score' => 2, 'order' => 2],
                    ['label' => 'Standardized infrastructure is available for major AI workloads, with defined provisioning, access controls, capacity management and integration with enterprise systems.', 'score' => 3, 'order' => 3],
                    ['label' => 'Scalable and resilient AI infrastructure supports production workloads across the organization, with automated provisioning, capacity monitoring, cost controls and defined service levels.', 'score' => 4, 'order' => 4],
                ],
            ],
            [
                'dimension_code' => 'D3',
                'question_text' => 'How are AI/ML models deployed and managed across their lifecycle?',
                'order' => 1,
                'weight' => 1,
                'include_in_pulse' => true,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'No defined model deployment or lifecycle-management process exists.', 'score' => 1, 'order' => 1],
                    ['label' => 'Models are deployed manually for selected pilots, with limited versioning or monitoring.', 'score' => 2, 'order' => 2],
                    ['label' => 'Defined MLOps processes support model versioning, testing, controlled deployment and production monitoring for major AI systems.', 'score' => 3, 'order' => 3],
                    ['label' => 'Standardized and automated MLOps pipelines manage model testing, deployment, monitoring, retraining and rollback across production AI systems.', 'score' => 4, 'order' => 4],
                ],
            ],

            // =========================================================
            // D4 — AI Governance
            // =========================================================
            [
                'dimension_code' => 'D4',
                'question_text' => 'Does the organization have a formal AI governance body?',
                'order' => 7,
                'weight' => 1,
                'include_in_pulse' => false,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'No AI governance body exists.', 'score' => 1, 'order' => 1],
                    ['label' => 'AI governance happens ad hoc through existing IT or risk committees.', 'score' => 2, 'order' => 2],
                    ['label' => 'A formal AI governance body exists with defined membership, mandate and meeting cadence.', 'score' => 3, 'order' => 3],
                    ['label' => 'A board-level AI governance body operates with documented authority, escalation paths and published outcomes.', 'score' => 4, 'order' => 4],
                ],
            ],
            [
                'dimension_code' => 'D4',
                'question_text' => 'How are AI risks identified, assessed, and managed?',
                'order' => 7,
                'weight' => 1,
                'include_in_pulse' => true,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'No AI-specific risk-management process exists.', 'score' => 1, 'order' => 1],
                    ['label' => 'Risks are handled informally by project teams.', 'score' => 2, 'order' => 2],
                    ['label' => 'A defined AI-risk taxonomy and process is integrated into enterprise risk management.', 'score' => 3, 'order' => 3],
                    ['label' => 'AI risk management is fully integrated into ERM, with a risk register, regular reviews, board reporting and clear ownership.', 'score' => 4, 'order' => 4],
                ],
            ],
            [
                'dimension_code' => 'D4',
                'question_text' => "Is the organization's AI portfolio aligned with ISO/IEC 42001:2023?",
                'order' => 8,
                'weight' => 1,
                'include_in_pulse' => false,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'ISO 42001 is not known or considered.', 'score' => 1, 'order' => 1],
                    ['label' => 'Awareness exists but no alignment assessment has been performed.', 'score' => 2, 'order' => 2],
                    ['label' => 'A gap assessment has been completed and remediation is underway.', 'score' => 3, 'order' => 3],
                    ['label' => 'Alignment is formally assessed and documented, and the organization has achieved or is actively pursuing certification.', 'score' => 4, 'order' => 4],
                ],
            ],

            // =========================================================
            // D5 — Responsible AI & Ethics
            // =========================================================
            [
                'dimension_code' => 'D5',
                'question_text' => 'Does the organization have an operationalized Responsible AI policy?',
                'order' => 11,
                'weight' => 1,
                'include_in_pulse' => true,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'No Responsible AI policy exists.', 'score' => 1, 'order' => 1],
                    ['label' => "A policy exists on paper but isn't systematically applied.", 'score' => 2, 'order' => 2],
                    ['label' => 'The policy is applied during AI development and procurement with documented checkpoints.', 'score' => 3, 'order' => 3],
                    ['label' => 'Responsible AI is embedded throughout the AI lifecycle, with audits, published principles and named accountability.', 'score' => 4, 'order' => 4],
                ],
            ],
            [
                'dimension_code' => 'D5',
                'question_text' => 'How does the organization test AI systems for bias and fairness?',
                'order' => 12,
                'weight' => 1,
                'include_in_pulse' => true,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'No bias testing is performed.', 'score' => 1, 'order' => 1],
                    ['label' => 'Some testing has occurred but there is no systematic process.', 'score' => 2, 'order' => 2],
                    ['label' => 'A defined bias-testing methodology is applied to all AI systems before deployment.', 'score' => 3, 'order' => 3],
                    ['label' => 'Bias and fairness are continuously monitored in production, with results documented, remediation actions tracked and governance oversight in place.', 'score' => 4, 'order' => 4],
                ],
            ],

            // =========================================================
            // D6 — Talent & Capabilities
            // =========================================================
            [
                'dimension_code' => 'D6',
                'question_text' => 'How well does the organization understand its current AI skills and capability gaps?',
                'order' => 1,
                'weight' => 1,
                'include_in_pulse' => false,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'The organization has no structured understanding of its AI skills or capability gaps.', 'score' => 1, 'order' => 1],
                    ['label' => 'Individual teams understand some capability gaps informally, but no organization-wide skills inventory exists.', 'score' => 2, 'order' => 2],
                    ['label' => 'A documented AI skills and capability inventory exists for key roles, and important gaps have been identified.', 'score' => 3, 'order' => 3],
                    ['label' => 'AI capabilities are systematically mapped across the organization, regularly reassessed and linked to workforce planning, recruitment, training and succession decisions.', 'score' => 4, 'order' => 4],
                ],
            ],
            [
                'dimension_code' => 'D6',
                'question_text' => 'How systematically does the organization develop AI skills among employees?',
                'order' => 1,
                'weight' => 1,
                'include_in_pulse' => true,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'No structured AI training is provided.', 'score' => 1, 'order' => 1],
                    ['label' => 'Some employees receive ad hoc AI training, but there is no coordinated programme.', 'score' => 2, 'order' => 2],
                    ['label' => 'Defined AI learning programmes exist for key employee groups and participation is tracked.', 'score' => 3, 'order' => 3],
                    ['label' => 'Role-based AI capability programmes are embedded into workforce development, with learning outcomes measured and programmes regularly updated based on capability needs.', 'score' => 4, 'order' => 4],
                ],
            ],

            // =========================================================
            // D7 — Use Case Maturity
            // =========================================================
            [
                'dimension_code' => 'D7',
                'question_text' => 'How does the organization identify and prioritize AI use cases?',
                'order' => 1,
                'weight' => 1,
                'include_in_pulse' => true,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'AI use cases are not systematically identified or prioritized.', 'score' => 1, 'order' => 1],
                    ['label' => 'Use cases are proposed opportunistically and selected mainly through individual sponsor interest.', 'score' => 2, 'order' => 2],
                    ['label' => 'A documented process evaluates AI use cases using defined criteria such as business value, feasibility and risk.', 'score' => 3, 'order' => 3],
                    ['label' => 'AI use-case prioritization is managed as an enterprise portfolio, with standardized business cases, comparative scoring, dependencies and regular portfolio review.', 'score' => 4, 'order' => 4],
                ],
            ],
            [
                'dimension_code' => 'D7',
                'question_text' => 'How successfully does the organization move AI use cases from experimentation into production?',
                'order' => 1,
                'weight' => 1,
                'include_in_pulse' => false,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'No AI use cases have progressed through a structured pilot-to-production process.', 'score' => 1, 'order' => 1],
                    ['label' => 'Isolated pilots exist, but most remain experimental and there is no repeatable path to production.', 'score' => 2, 'order' => 2],
                    ['label' => 'A defined process exists for evaluating pilots and moving successful AI use cases into production, with several examples completed.', 'score' => 3, 'order' => 3],
                    ['label' => 'The organization repeatedly moves validated AI use cases into production through a governed, scalable process across multiple business areas.', 'score' => 4, 'order' => 4],
                ],
            ],

            // =========================================================
            // D8 — Organizational Culture
            // =========================================================
            [
                'dimension_code' => 'D8',
                'question_text' => 'How supportive is the organization of responsible AI experimentation and learning?',
                'order' => 1,
                'weight' => 1,
                'include_in_pulse' => true,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'AI experimentation is generally absent or discouraged.', 'score' => 1, 'order' => 1],
                    ['label' => 'Experimentation occurs within isolated teams, depending mainly on individual initiative.', 'score' => 2, 'order' => 2],
                    ['label' => 'Defined mechanisms such as pilots, sandboxes or innovation programmes support controlled AI experimentation and learning.', 'score' => 3, 'order' => 3],
                    ['label' => 'Responsible experimentation is embedded in the organizational culture, with dedicated mechanisms, shared learning and successful experiments systematically considered for scaling.', 'score' => 4, 'order' => 4],
                ],
            ],
            [
                'dimension_code' => 'D8',
                'question_text' => 'How effectively do business and technical teams collaborate on AI initiatives?',
                'order' => 1,
                'weight' => 1,
                'include_in_pulse' => false,
                'pulse_order' => null,
                'help_text' => null,
                'is_active' => true,
                'options' => [
                    ['label' => 'AI-related work occurs in silos with little or no cross-functional collaboration.', 'score' => 1, 'order' => 1],
                    ['label' => 'Collaboration happens informally on selected projects but depends on individual relationships.', 'score' => 2, 'order' => 2],
                    ['label' => 'Cross-functional teams and defined responsibilities are used for major AI initiatives.', 'score' => 3, 'order' => 3],
                    ['label' => 'Cross-functional AI delivery is standard practice, with business, technical and control functions jointly involved from use-case definition through deployment and monitoring.', 'score' => 4, 'order' => 4],
                ],
            ],

            // =========================================================
            // D9 — Budget & Investment
            // =========================================================
            [
                'dimension_code' => 'D9',
                'question_text' => 'How is AI investment funded within the organization?',
                'order' => 1,
                'weight' => 1,
                'include_in_pulse' => true,
                'pulse_order' => null,
                'help_text' => 'Consider whether AI funding is allocated only to individual projects when needed or whether the organization maintains dedicated, predictable funding for AI initiatives and capabilities.',
                'is_active' => true,
                'options' => [
                    ['label' => 'There is no dedicated AI funding; initiatives compete for ad hoc project funding.', 'score' => 1, 'order' => 1],
                    ['label' => 'Funding is provided case-by-case for selected AI experiments or projects.', 'score' => 2, 'order' => 2],
                    ['label' => 'A defined AI budget supports prioritized initiatives and capability development, with periodic review.', 'score' => 3, 'order' => 3],
                    ['label' => 'AI is funded through a dedicated multi-year portfolio aligned with strategic priorities and integrated into enterprise investment planning.', 'score' => 4, 'order' => 4],
                ],
            ],

            // =========================================================
            // D10 — Change & Adoption Management
            // =========================================================
            [
                'dimension_code' => 'D10',
                'question_text' => 'Is a structured change management methodology applied to AI programme delivery?',
                'order' => 1,
                'weight' => 1,
                'include_in_pulse' => true,
                'pulse_order' => null,
                'help_text' => 'Consider whether AI deployments include formal stakeholder analysis, communication, training and resistance management rather than focusing only on technical delivery.',
                'is_active' => true,
                'options' => [
                    ['label' => 'No change management is applied to AI programmes.', 'score' => 1, 'order' => 1],
                    ['label' => 'Ad hoc communication and training activities occur around AI launches without structured methodology.', 'score' => 2, 'order' => 2],
                    ['label' => 'A structured change management methodology (e.g., PROSCI, Kotter) is applied to major AI programmes.', 'score' => 3, 'order' => 3],
                    ['label' => 'An enterprise-wide AI adoption framework governs all AI deployments — with stakeholder mapping, communication plans, training delivery, resistance management, and adoption measurement embedded from programme initiation.', 'score' => 4, 'order' => 4],
                ],
            ],
            [
                'dimension_code' => 'D10',
                'question_text' => 'How does the organization measure AI adoption, not just deployment?',
                'order' => 1,
                'weight' => 1,
                'include_in_pulse' => false,
                'pulse_order' => null,
                'help_text' => 'Think beyond whether the technology was successfully launched. Consider whether employees actually use it and whether usage, satisfaction and adoption outcomes are measured.',
                'is_active' => true,
                'options' => [
                    ['label' => 'Adoption is not measured — success is defined as go-live.', 'score' => 1, 'order' => 1],
                    ['label' => 'Adoption is measured informally through user feedback with no structured metrics.', 'score' => 2, 'order' => 2],
                    ['label' => 'Adoption KPIs (usage rates, task completion, user satisfaction) are defined and tracked per AI deployment.', 'score' => 3, 'order' => 3],
                    ['label' => 'Adoption measurement is systematic, reported alongside technical performance metrics, and feeds into iterative improvement cycles for each production AI system.', 'score' => 4, 'order' => 4],
                ],
            ],

            // =========================================================
            // D11 — AI Security & Resilience
            // =========================================================
            [
                'dimension_code' => 'D11',
                'question_text' => 'Does the organization perform AI-specific threat modelling?',
                'order' => 1,
                'weight' => 1,
                'include_in_pulse' => true,
                'pulse_order' => null,
                'help_text' => 'Consider whether security analysis explicitly covers AI-specific threats such as adversarial inputs, data poisoning, model vulnerabilities and risks from third-party AI components.',
                'is_active' => true,
                'options' => [
                    ['label' => 'No AI-specific threat modelling is performed.', 'score' => 1, 'order' => 1],
                    ['label' => 'AI systems are covered only by general cybersecurity assessments.', 'score' => 2, 'order' => 2],
                    ['label' => 'AI-specific threat modelling is performed for key AI systems and documented before production deployment.', 'score' => 3, 'order' => 3],
                    ['label' => 'AI-specific threat modelling is embedded throughout the lifecycle, supported by red-team exercises and regularly refreshed as threats evolve.', 'score' => 4, 'order' => 4],
                ],
            ],
            [
                'dimension_code' => 'D11',
                'question_text' => 'How does the organization protect AI systems against prompt injection and adversarial inputs?',
                'order' => 1,
                'weight' => 1,
                'include_in_pulse' => false,
                'pulse_order' => null,
                'help_text' => 'Consider controls against malicious prompts, jailbreak attempts, data extraction and manipulated inputs targeting AI systems.',
                'is_active' => true,
                'options' => [
                    ['label' => 'Prompt injection and adversarial-input risks are not formally considered.', 'score' => 1, 'order' => 1],
                    ['label' => 'Teams are aware of these risks, but no consistent technical controls are implemented.', 'score' => 2, 'order' => 2],
                    ['label' => 'Controls such as input validation, filtering and isolation are implemented for relevant AI systems.', 'score' => 3, 'order' => 3],
                    ['label' => 'A comprehensive AI-security approach includes preventive controls, red-team testing, monitoring and incident-response procedures for prompt injection and adversarial attacks.', 'score' => 4, 'order' => 4],
                ],
            ],
        ];

        DB::transaction(function () use ($questions) {
            foreach ($questions as $data) {

                $dimension = Dimension::where(
                    'code',
                    $data['dimension_code']
                )->firstOrFail();

                $options = $data['options'];

                unset($data['dimension_code'], $data['options']);

                $question = Question::create([
                    'dimension_id' => $dimension->id,
                    'question_text' => $data['question_text'],
                    'order' => $data['order'],
                    'weight' => $data['weight'],
                    'include_in_pulse' => $data['include_in_pulse'],
                    'pulse_order' => $data['pulse_order'],
                    'help_text' => $data['help_text'],
                    'is_active' => $data['is_active'],
                ]);

                foreach ($options as $option) {
                    $question->answerOptions()->create($option);
                }
            }
        });
    }
}