<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryAIReadinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    $path = storage_path('app/data/master_ai_readiness.csv');

    if (!file_exists($path)) {
        throw new \Exception("CSV file not found: " . $path);
    }

    DB::table('country_ai_readiness_scores')->truncate();

    $file = fopen($path, 'r');
    $header = fgetcsv($file);

// remove hidden BOM + trim spaces from column names
$header = array_map(function ($value) {
    return trim(str_replace("\xEF\xBB\xBF", '', $value));
}, $header);

    $count = 0;

    while (($row = fgetcsv($file)) !== false) {
        $data = array_combine($header, $row);

        DB::table('country_ai_readiness_scores')->insert([
            'country' => $data['country'],
            'year' => (int) $data['year'],
            'final_rank' => $data['final_rank'] !== '' ? (int) $data['final_rank'] : null,
            'official_rank' => $data['official_rank'] !== '' ? (int) $data['official_rank'] : null,
            'score' => (float) $data['score'],
            'score_type' => $data['score_type'] ?? null,
            'missing_values' => $data['missing_values'] !== '' ? (int) $data['missing_values'] : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $count++;
    }

    fclose($file);

    echo "Inserted {$count} rows\n";
}
} 