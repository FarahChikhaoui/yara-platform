<?php

namespace App\Http\Controllers;

use App\Models\BenchmarkDataset;
use App\Models\CountryAIReadinessScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\AuditLogger;

class AdminCountryDataController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Benchmark datasets
        |--------------------------------------------------------------------------
        */

        $datasets = BenchmarkDataset::query()
            ->orderByDesc('year')
            ->get()
            ->map(function ($dataset) {

                $dataset->countries_count =
                    CountryAIReadinessScore::query()
                        ->where('year', $dataset->year)
                        ->distinct('country')
                        ->count('country');

                return $dataset;
            });

        $activeDataset = $datasets->firstWhere(
            'is_active',
            true
        );


        /*
        |--------------------------------------------------------------------------
        | Existing overview statistics
        |--------------------------------------------------------------------------
        */

        $latestYear = CountryAIReadinessScore::max('year');

        $years = CountryAIReadinessScore::query()
            ->select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $countriesCount = $latestYear
            ? CountryAIReadinessScore::where(
                'year',
                $latestYear
            )
                ->distinct('country')
                ->count('country')
            : 0;

        $totalRecords = CountryAIReadinessScore::count();


        /*
        |--------------------------------------------------------------------------
        | Country records
        |--------------------------------------------------------------------------
        */

        $records = CountryAIReadinessScore::query()
            ->when(
                $request->filled('year'),
                fn ($query) => $query->where(
                    'year',
                    $request->integer('year')
                )
            )
            ->when(
                $request->filled('search'),
                fn ($query) => $query->where(
                    'country',
                    'like',
                    '%' . $request->search . '%'
                )
            )
            ->orderByDesc('year')
            ->orderBy('final_rank')
            ->paginate(25)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | AJAX filtering
        |--------------------------------------------------------------------------
        */

        if ($request->ajax()) {
            return view(
                'admin.country-data.partials.records',
                compact('records')
            );
        }

$datasets = BenchmarkDataset::query()
    ->orderByDesc('year')
    ->get()
    ->map(function ($dataset) {

        $dataset->records_count =
            CountryAIReadinessScore::where(
                'year',
                $dataset->year
            )->count();

        return $dataset;
    });

$activeDataset = $datasets->firstWhere('is_active', true);

        return view('admin.country-data.index', compact(
    'latestYear',
    'years',
    'countriesCount',
    'totalRecords',
    'records',
    'datasets',
    'activeDataset'

        ));
        

    }


    /*
    |--------------------------------------------------------------------------
    | Activate benchmark dataset
    |--------------------------------------------------------------------------
    */

    public function activate(BenchmarkDataset $dataset)
    {
        DB::transaction(function () use ($dataset) {

            BenchmarkDataset::query()->update([
                'is_active' => false,
            ]);

            $dataset->update([
                'is_active' => true,
            ]);
        });
AuditLogger::log(
    'benchmark.activated',
    $dataset,
    "Benchmark dataset {$dataset->year} activated.",
    [
        'year' => $dataset->year,
        'source' => $dataset->source,
    ]
);

        return redirect()
            ->route('admin.country-data.index')
            ->with(
                'success',
                "The {$dataset->year} benchmark dataset is now active."
            );
    }
public function createImport()
{
    $activeDataset = BenchmarkDataset::where(
        'is_active',
        true
    )->first();

    return view(
        'admin.country-data.import',
        compact('activeDataset')
    );
}
public function storeImport(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | 1. Validate uploaded form
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'year' => [
            'required',
            'integer',
            'min:2019',
            'max:' . (now()->year + 1),
        ],

        'source' => [
            'required',
            'string',
            'max:255',
        ],

        'dataset_file' => [
            'required',
            'file',
            'mimes:csv,txt',
            'max:10240',
        ],

        'activate' => [
            'nullable',
            'boolean',
        ],
    ]);


    $year = (int) $validated['year'];
    $source = trim($validated['source']);
    $shouldActivate = $request->boolean('activate');


    /*
    |--------------------------------------------------------------------------
    | 2. Reject an existing dataset year
    |--------------------------------------------------------------------------
    |
    | We deliberately do NOT overwrite benchmark data.
    |
    */

    $datasetAlreadyExists = BenchmarkDataset::where(
        'year',
        $year
    )->exists();

    $countryDataAlreadyExists = CountryAIReadinessScore::where(
        'year',
        $year
    )->exists();

    if ($datasetAlreadyExists || $countryDataAlreadyExists) {
        return back()
            ->withInput()
            ->withErrors([
                'year' => "A benchmark dataset for {$year} already exists. Existing benchmark data cannot be overwritten by an import.",
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 3. Open CSV
    |--------------------------------------------------------------------------
    */

    $file = $request->file('dataset_file');

    $handle = fopen($file->getRealPath(), 'r');

    if ($handle === false) {
        return back()
            ->withInput()
            ->withErrors([
                'dataset_file' => 'The uploaded CSV could not be opened.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 4. Read + normalize CSV header
    |--------------------------------------------------------------------------
    */

    $header = fgetcsv($handle);

    if ($header === false) {
        fclose($handle);

        return back()
            ->withInput()
            ->withErrors([
                'dataset_file' => 'The uploaded CSV is empty.',
            ]);
    }


    /*
     * Remove UTF-8 BOM if present and normalize column names.
     */
    $header = array_map(function ($column) {
        $column = preg_replace('/^\xEF\xBB\xBF/', '', $column);

        return strtolower(trim($column));
    }, $header);


    /*
     * These are the fields YARA currently needs.
     */
    $requiredColumns = [
        'year',
        'final_rank',
        'official_rank',
        'country',
        'score',
        'score_type',
        'missing_values',
    ];


    $missingColumns = array_diff(
        $requiredColumns,
        $header
    );


    if (!empty($missingColumns)) {
        fclose($handle);

        return back()
            ->withInput()
            ->withErrors([
                'dataset_file' =>
                    'Missing required CSV columns: '
                    . implode(', ', $missingColumns),
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 5. Validate every CSV row BEFORE touching the database
    |--------------------------------------------------------------------------
    */

    $rows = [];
    $seenCountries = [];
    $rowNumber = 1;

    while (($data = fgetcsv($handle)) !== false) {

        $rowNumber++;


        /*
         * Ignore completely empty CSV rows.
         */
        if (
            count($data) === 1
            && trim((string) $data[0]) === ''
        ) {
            continue;
        }


        /*
         * Every row must have exactly the same number
         * of values as the header.
         */
        if (count($data) !== count($header)) {
            fclose($handle);

            return back()
                ->withInput()
                ->withErrors([
                    'dataset_file' =>
                        "Invalid CSV structure on row {$rowNumber}.",
                ]);
        }


        $row = array_combine($header, $data);

        if ($row === false) {
            fclose($handle);

            return back()
                ->withInput()
                ->withErrors([
                    'dataset_file' =>
                        "Unable to read CSV row {$rowNumber}.",
                ]);
        }


        /*
         * Normalize values.
         */
        $country = trim((string) $row['country']);
        $rowYear = trim((string) $row['year']);
        $score = trim((string) $row['score']);


        /*
         * Country required.
         */
        if ($country === '') {
            fclose($handle);

            return back()
                ->withInput()
                ->withErrors([
                    'dataset_file' =>
                        "Country is missing on row {$rowNumber}.",
                ]);
        }


        /*
         * Every row must belong to the year selected
         * by the administrator.
         */
        if (
            !is_numeric($rowYear)
            || (int) $rowYear !== $year
        ) {
            fclose($handle);

            return back()
                ->withInput()
                ->withErrors([
                    'dataset_file' =>
                        "Row {$rowNumber} contains year {$rowYear}, but the selected dataset year is {$year}.",
                ]);
        }


        /*
         * Score must be numeric and between 0 and 100.
         */
        if (
            !is_numeric($score)
            || (float) $score < 0
            || (float) $score > 100
        ) {
            fclose($handle);

            return back()
                ->withInput()
                ->withErrors([
                    'dataset_file' =>
                        "Invalid score for {$country} on row {$rowNumber}. Scores must be between 0 and 100.",
                ]);
        }


        /*
         * Prevent duplicate countries inside the same file.
         */
        $countryKey = strtolower($country);

        if (isset($seenCountries[$countryKey])) {
            fclose($handle);

            return back()
                ->withInput()
                ->withErrors([
                    'dataset_file' =>
                        "Duplicate country '{$country}' found on row {$rowNumber}.",
                ]);
        }

        $seenCountries[$countryKey] = true;


        /*
         * Validate optional integer values.
         */
        foreach (
            [
                'final_rank',
                'official_rank',
                'missing_values',
            ] as $integerColumn
        ) {

            $value = trim(
                (string) ($row[$integerColumn] ?? '')
            );

            if (
                $value !== ''
                && !ctype_digit($value)
            ) {
                fclose($handle);

                return back()
                    ->withInput()
                    ->withErrors([
                        'dataset_file' =>
                            "Invalid {$integerColumn} value for {$country} on row {$rowNumber}.",
                    ]);
            }
        }


        /*
         * Build clean database row.
         *
         * Extra ETL columns in the CSV are intentionally
         * allowed but are not stored by this table.
         */
        $rows[] = [
            'country' => $country,
            'year' => $year,

            'final_rank' =>
                trim((string) $row['final_rank']) !== ''
                    ? (int) $row['final_rank']
                    : null,

            'official_rank' =>
                trim((string) $row['official_rank']) !== ''
                    ? (int) $row['official_rank']
                    : null,

            'score' => round((float) $score, 2),

            'score_type' =>
                trim((string) $row['score_type']) !== ''
                    ? trim((string) $row['score_type'])
                    : null,

            'missing_values' =>
                trim((string) $row['missing_values']) !== ''
                    ? (int) $row['missing_values']
                    : null,

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }


    fclose($handle);


    /*
    |--------------------------------------------------------------------------
    | 6. Reject an empty dataset
    |--------------------------------------------------------------------------
    */

    if (empty($rows)) {
        return back()
            ->withInput()
            ->withErrors([
                'dataset_file' =>
                    'The uploaded CSV does not contain any country records.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 7. Import everything atomically
    |--------------------------------------------------------------------------
    |
    | If ANY database operation fails, nothing is imported.
    |
    */
$importedDataset = null;

try {

    DB::transaction(function () use (
    $rows,
    $year,
    $source,
    $shouldActivate,
    &$importedDataset
) {

            /*
             * Safety check again INSIDE transaction.
             */
            if (
                BenchmarkDataset::where('year', $year)->exists()
                || CountryAIReadinessScore::where('year', $year)->exists()
            ) {
                throw new \RuntimeException(
                    "A benchmark dataset for {$year} already exists."
                );
            }


            /*
             * Insert country benchmark records.
             */
            CountryAIReadinessScore::insert($rows);


            /*
             * If requested, deactivate the previous benchmark.
             */
            if ($shouldActivate) {
                BenchmarkDataset::query()->update([
                    'is_active' => false,
                ]);
            }


            /*
             * Register imported dataset.
             */
$importedDataset = BenchmarkDataset::create([                'name' =>
                    "Government AI Readiness Index {$year}",

                'source' => $source,

                'year' => $year,

                'etl_method' => 'Python ETL',

                'is_active' => $shouldActivate,

                'imported_at' => now(),
            ]);
        });

    } catch (\Throwable $exception) {

        report($exception);

        return back()
            ->withInput()
            ->withErrors([
                'dataset_file' =>
                    'The dataset could not be imported. No benchmark data was changed.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 8. Success
    |--------------------------------------------------------------------------
    */
AuditLogger::log(
    'benchmark.imported',
    $importedDataset,
    "Benchmark dataset {$year} imported.",
    [
        'year' => $year,
        'source' => $source,
        'records_count' => count($rows),
        'activated_on_import' => $shouldActivate,
    ]
);
    $message =
        "{$year} benchmark dataset imported successfully. "
        . count($rows)
        . " country records were added.";

    if ($shouldActivate) {
        $message .= " {$year} is now the active benchmark.";
    }


    return redirect()
        ->route('admin.country-data.index')
        ->with('success', $message);
}
public function destroyDataset(BenchmarkDataset $dataset)
{
    /*
     * The active benchmark must never be deleted.
     */
    if ($dataset->is_active) {
        return redirect()
            ->route('admin.country-data.index')
            ->withErrors([
                'dataset' =>
                    'The active benchmark dataset cannot be deleted.',
            ]);
    }
$deletedDatasetId = $dataset->id;
$deletedYear = $dataset->year;
$deletedSource = $dataset->source;
    try {

        DB::transaction(function () use ($dataset) {

            /*
             * Delete all benchmark records belonging
             * to this dataset year.
             */
            CountryAIReadinessScore::where(
                'year',
                $dataset->year
            )->delete();

            /*
             * Delete the dataset registry entry.
             */
            $dataset->delete();

        });

    } catch (\Throwable $e) {

        report($e);

        return redirect()
            ->route('admin.country-data.index')
            ->withErrors([
                'dataset' =>
                    'The dataset could not be deleted. No data was changed.',
            ]);
    }
AuditLogger::log(
    'benchmark.deleted',
    null,
    "Benchmark dataset {$deletedYear} deleted.",
    [
        'dataset_id' => $deletedDatasetId,
        'year' => $deletedYear,
        'source' => $deletedSource,
    ]
);
    return redirect()
        ->route('admin.country-data.index')
        ->with(
            'success',
"Benchmark dataset {$deletedYear} was deleted successfully."        );
}
}