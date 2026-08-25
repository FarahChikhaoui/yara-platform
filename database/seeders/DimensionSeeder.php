<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class DimensionSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('dimensions')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('dimensions')->insert([
            [
                'id' => 1,
                'code' => 'D1',
                'name' => 'AI Strategy & Vision',
                'weight' => 13.0,
                'description' => 'Board-approved AI strategy, business alignment, executive sponsorship, funded multi-year roadmap,',
                'order' => 0,
                'created_at' => '2026-06-30 13:17:32',
                'updated_at' => '2026-06-30 13:17:32',
            ],
            [
                'id' => 2,
                'code' => 'D2',
                'name' => 'Data Readiness',
                'weight' => 16.0,
                'description' => 'Data accuracy, governance, accessibility, integration, cataloguing',
                'order' => 0,
                'created_at' => '2026-06-30 13:18:15',
                'updated_at' => '2026-06-30 13:18:15',
            ],
            [
                'id' => 3,
                'code' => 'D3',
                'name' => 'Technology Infrastructure & MLOps',
                'weight' => 10.0,
                'description' => 'Cloud, compute, storage, integration, and MLOps capabilities for building, deploying, monitoring, and reliably operating AI solutions in production',
                'order' => 0,
                'created_at' => '2026-06-30 13:19:22',
                'updated_at' => '2026-06-30 13:19:22',
            ],
            [
                'id' => 4,
                'code' => 'D4',
                'name' => 'Ai Governance',
                'weight' => 11.0,
                'description' => null,
                'order' => 0,
                'created_at' => '2026-06-30 13:19:41',
                'updated_at' => '2026-06-30 13:19:41',
            ],
            [
                'id' => 5,
                'code' => 'D5',
                'name' => 'Responsible AI & Ethics',
                'weight' => 9.0,
                'description' => null,
                'order' => 0,
                'created_at' => '2026-06-30 13:20:24',
                'updated_at' => '2026-06-30 13:20:24',
            ],
            [
                'id' => 6,
                'code' => 'D6',
                'name' => 'Talent & Capabilities',
                'weight' => 10.0,
                'description' => null,
                'order' => 0,
                'created_at' => '2026-06-30 13:20:45',
                'updated_at' => '2026-06-30 13:20:45',
            ],
            [
                'id' => 7,
                'code' => 'D7',
                'name' => 'Use Case Maturity',
                'weight' => 10.0,
                'description' => null,
                'order' => 0,
                'created_at' => '2026-06-30 13:21:04',
                'updated_at' => '2026-06-30 13:21:04',
            ],
            [
                'id' => 8,
                'code' => 'D8',
                'name' => 'Organizational Culture',
                'weight' => 8.0,
                'description' => null,
                'order' => 0,
                'created_at' => '2026-06-30 13:21:22',
                'updated_at' => '2026-06-30 13:21:22',
            ],
            [
                'id' => 9,
                'code' => 'D9',
                'name' => 'Budget & Investment',
                'weight' => 6.0,
                'description' => null,
                'order' => 0,
                'created_at' => '2026-06-30 13:21:39',
                'updated_at' => '2026-06-30 13:22:34',
            ],
            [
                'id' => 10,
                'code' => 'D10',
                'name' => 'Change & Adoption Management',
                'weight' => 4.0,
                'description' => null,
                'order' => 0,
                'created_at' => '2026-06-30 13:21:58',
                'updated_at' => '2026-06-30 13:22:20',
            ],
            [
                'id' => 11,
                'code' => 'D11',
                'name' => 'AI Security & Resilience',
                'weight' => 3.0,
                'description' => null,
                'order' => 0,
                'created_at' => '2026-06-30 13:22:49',
                'updated_at' => '2026-06-30 13:22:49',
            ],
        ]);
    }
}
