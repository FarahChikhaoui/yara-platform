<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recommendation_rules', function (Blueprint $table) {
            $table->string('category')
                ->nullable()
                ->after('business_rationale');

            $table->text('expected_outcomes')
                ->nullable()
                ->after('category');
        });
    }

    public function down(): void
    {
        Schema::table('recommendation_rules', function (Blueprint $table) {
            $table->dropColumn([
                'category',
                'expected_outcomes',
            ]);
        });
    }
};