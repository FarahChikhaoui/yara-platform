<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MaturityLevelSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('maturity_levels')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('maturity_levels')->insert([
            [
                'level' => 1,
                'name' => 'Ad Hoc',
                'min_score' => 0.0,
                'max_score' => 20.0,
                'description' => 'No established AI foundations. Experimentation may exist, but there is no structured AI strategy, governance, or consistent data readiness. AI investment remains high risk.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 2,
                'name' => 'Initial',
                'min_score' => 20.0,
                'max_score' => 39.99,
                'description' => 'Leadership awareness and interest in AI exist, with isolated pilots or initiatives. Governance and data practices remain limited or inconsistent, and AI use cases are not yet systematically prioritized.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 3,
                'name' => 'Defined',
                'min_score' => 40.0,
                'max_score' => 59.99,
                'description' => 'A documented AI strategy and supporting foundations are in place. Governance, data capabilities and talent are developing, while AI initiatives are becoming more structured but scalability is not yet fully demonstrated.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 4,
                'name' => 'Managed',
                'min_score' => 60.0,
                'max_score' => 79.99,
                'description' => 'AI delivers measurable business value through multiple production use cases. Governance is operational, data pipelines are reliable and controlled, and the organization is increasingly focused on scaling AI capabilities.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 5,
                'name' => 'Optimizing',
                'min_score' => 80.0,
                'max_score' => 100.0,
                'description' => 'AI operates as a strategic differentiator. The organization continuously improves its AI capabilities, monitors performance and maturity, scales innovation, and integrates AI into strategic decision-making.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}