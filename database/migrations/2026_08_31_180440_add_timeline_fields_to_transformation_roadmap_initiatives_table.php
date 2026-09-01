<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roadmap_initiatives', function (Blueprint $table) {
            $table->unsignedInteger('start_month')
                ->nullable()
                ->after('phase');

            $table->unsignedInteger('duration_months')
                ->nullable()
                ->after('start_month');
        });
    }

    public function down(): void
    {
        Schema::table('roadmap_initiatives', function (Blueprint $table) {
            $table->dropColumn([
                'start_month',
                'duration_months',
            ]);
        });
    }
};