<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class MaturityLevelSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('maturity_levels')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('maturity_levels')->insert([
            [
                'id' => 1,
                'level' => 1,
                'name' => 'Ad Hoc',
                'min_score' => 0.0,
                'max_score' => 20.0,
                'description' => 'bgjh',
                'created_at' => '2026-06-30 13:32:53',
                'updated_at' => '2026-06-30 13:32:53',
            ],
            [
                'id' => 2,
                'level' => 2,
                'name' => 'Initial',
                'min_score' => 20.0,
                'max_score' => 39.99,
                'description' => null,
                'created_at' => '2026-06-30 13:33:24',
                'updated_at' => '2026-06-30 13:33:24',
            ],
            [
                'id' => 3,
                'level' => 3,
                'name' => 'Defined',
                'min_score' => 40.0,
                'max_score' => 59.99,
                'description' => null,
                'created_at' => '2026-06-30 13:33:44',
                'updated_at' => '2026-06-30 13:33:44',
            ],
            [
                'id' => 4,
                'level' => 4,
                'name' => 'Managed',
                'min_score' => 60.0,
                'max_score' => 79.99,
                'description' => null,
                'created_at' => '2026-06-30 13:34:03',
                'updated_at' => '2026-06-30 13:34:03',
            ],
            [
                'id' => 5,
                'level' => 5,
                'name' => 'Optimizing',
                'min_score' => 80.0,
                'max_score' => 100.0,
                'description' => null,
                'created_at' => '2026-06-30 13:34:28',
                'updated_at' => '2026-06-30 13:34:28',
            ],
        ]);
    }
}
