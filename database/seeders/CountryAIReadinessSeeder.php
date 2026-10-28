<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryAIReadinessSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/data/master_ai_readiness.csv');

        if (!file_exists($path)) {
            throw new \Exception("CSV file not found: {$path}");
        }

        $file = fopen($path, 'r');

        if ($file === false) {
            throw new \Exception("Unable to open CSV file: {$path}");
        }

        $header = fgetcsv($file);

        if ($header === false) {
            fclose($file);
            throw new \Exception("CSV file is empty: {$path}");
        }

        // Remove hidden BOM and trim spaces from column names
        $header = array_map(function ($value) {
            return trim(str_replace("\xEF\xBB\xBF", '', $value));
        }, $header);

        DB::table('country_ai_readiness_scores')->truncate();

        $count = 0;

        while (($row = fgetcsv($file)) !== false) {

            // Skip malformed rows
            if (count($row) !== count($header)) {
                continue;
            }

            $data = array_combine($header, $row);

            DB::table('country_ai_readiness_scores')->insert([
                'country' => $data['country'],
                'year' => (int) $data['year'],

                'final_rank' =>
                    $data['final_rank'] !== ''
                        ? (int) $data['final_rank']
                        : null,

                'official_rank' =>
                    $data['official_rank'] !== ''
                        ? (int) $data['official_rank']
                        : null,

                'score' => (float) $data['score'],

                'score_type' =>
                    $data['score_type'] ?? null,

                'missing_values' =>
                    $data['missing_values'] !== ''
                        ? (int) $data['missing_values']
                        : null,

                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $count++;
        }

        fclose($file);

        $this->command?->info(
            "Inserted {$count} country AI readiness rows."
        );
    }
}