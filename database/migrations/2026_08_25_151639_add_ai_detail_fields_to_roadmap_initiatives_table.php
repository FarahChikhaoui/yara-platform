<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roadmap_initiatives', function (Blueprint $table) {

            $table->json('recommended_actions')
                ->nullable();

            $table->text('expected_outcome')
                ->nullable();

            $table->json('success_metrics')
                ->nullable();

            $table->json('dependencies')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('roadmap_initiatives', function (Blueprint $table) {
            $table->dropColumn([
                'recommended_actions',
                'expected_outcome',
                'success_metrics',
                'dependencies',
            ]);
        });
    }
};