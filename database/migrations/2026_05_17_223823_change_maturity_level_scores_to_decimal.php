<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE maturity_levels MODIFY min_score DECIMAL(8,2)');
        DB::statement('ALTER TABLE maturity_levels MODIFY max_score DECIMAL(8,2)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE maturity_levels MODIFY min_score INT');
        DB::statement('ALTER TABLE maturity_levels MODIFY max_score INT');
    }
};