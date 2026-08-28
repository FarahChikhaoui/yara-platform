<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BenchmarkDataset;

class BenchmarkDatasetSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(2019, 2024) as $year) {
            BenchmarkDataset::updateOrCreate(
                [
                    'source' => 'Oxford Insights',
                    'year' => $year,
                ],
                [
                    'name' => "Government AI Readiness Index {$year}",
                    'etl_method' => 'Python ETL',
                    'is_active' => false,
                    'imported_at' => now(),
                ]
            );
        }
    }
}