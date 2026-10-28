<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DimensionSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('dimensions')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('dimensions')->insert([
            [
                'code' => 'D1',
                'name' => 'AI Strategy & Vision',
                'description' => 'Board-approved AI strategy, business alignment, executive sponsorship, funded multi-year roadmap, prioritization and monitoring of the external AI landscape.',
                'weight' => 13,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'D2',
                'name' => 'Data Readiness',
                'description' => 'Data quality, governance, accessibility, integration, catalogue, privacy, lineage, monitoring, provenance, bias and consent management.',
                'weight' => 16,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'D3',
                'name' => 'Technology Infrastructure & MLOps',
                'description' => 'Cloud, compute, storage, integration and MLOps capabilities required to build, deploy, monitor and reliably operate production AI systems.',
                'weight' => 10,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'D4',
                'name' => 'AI Governance',
                'description' => 'Governance structures, policies, accountability, risk management, audit, incident management, compliance and board-level visibility.',
                'weight' => 11,
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'D5',
                'name' => 'Responsible AI & Ethics',
                'description' => 'Fairness, transparency, explainability, human oversight, bias testing, training-data standards and operationalization of responsible AI principles.',
                'weight' => 9,
                'order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'D6',
                'name' => 'Talent & Capabilities',
                'description' => 'AI skills inventory, team structure, training, partnerships, talent retention and collaboration between business and technical expertise.',
                'weight' => 10,
                'order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'D7',
                'name' => 'Use Case Maturity',
                'description' => 'Systematic identification, business-case prioritization, governed piloting, production deployment and measurement of AI use-case value and ROI.',
                'weight' => 10,
                'order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'D8',
                'name' => 'Organizational Culture',
                'description' => 'Responsible experimentation, cross-functional collaboration, change adoption, leadership engagement, AI literacy and employee participation.',
                'weight' => 8,
                'order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'D9',
                'name' => 'Budget & Investment',
                'description' => 'Dedicated and adequate AI funding, multi-year investment planning, allocation, ROI monitoring, innovation funding and portfolio oversight.',
                'weight' => 6,
                'order' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'D10',
                'name' => 'Change & Adoption Management',
                'description' => 'Structured change methodology, stakeholder engagement, communication, training, resistance management and measurement of AI adoption.',
                'weight' => 4,
                'order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'D11',
                'name' => 'AI Security & Resilience',
                'description' => 'Protection against adversarial attacks, data poisoning, prompt injection, supply-chain risks and operational failures through secure MLOps, threat modelling, red teaming and incident response.',
                'weight' => 3,
                'order' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}