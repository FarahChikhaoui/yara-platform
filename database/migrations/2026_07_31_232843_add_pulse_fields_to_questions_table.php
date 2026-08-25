<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {

            /*
             * Should this question appear
             * in the free Pulse Check?
             */
            $table->boolean('include_in_pulse')
                ->default(false)
                ->after('weight');

            /*
             * Display order inside the Pulse Check.
             */
            $table->unsignedSmallInteger('pulse_order')
                ->nullable()
                ->after('include_in_pulse');

        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {

            $table->dropColumn([
                'include_in_pulse',
                'pulse_order'
            ]);

        });
    }
};