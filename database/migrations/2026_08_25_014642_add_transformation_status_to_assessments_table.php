<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {

            /*
             * Tracks the Transformation Roadmap workflow.
             *
             * null          = normal self-assessment
             * planning      = transformation started, planning inputs not submitted
             * submitted     = client submitted planning inputs
             * in_review     = consultant is reviewing the request
             * roadmap_ready = consultant finalized/released the roadmap
             */
            $table->string('transformation_status')
                ->nullable()
                ->after('engagement_type');
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn('transformation_status');
        });
    }
};