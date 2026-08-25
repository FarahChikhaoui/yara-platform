<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_recommendations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assessment_id')
                ->constrained('assessments')
                ->cascadeOnDelete();

            $table->foreignId('recommendation_rule_id')
                ->constrained('recommendation_rules')
                ->cascadeOnDelete();

            $table->foreignId('dimension_id')
                ->constrained('dimensions')
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->nullable()
                ->constrained('questions')
                ->nullOnDelete();

            $table->unsignedTinyInteger('triggered_answer_score')
                ->nullable();

            $table->decimal('calculated_priority', 10, 2)
                ->default(0);

            $table->unsignedInteger('priority_rank')
                ->nullable();

            $table->enum('status', [
                'recommended',
                'planned',
                'in_progress',
                'completed',
                'dismissed',
            ])->default('recommended');

            /*
             * Snapshot fields preserve the generated roadmap even if
             * the original recommendation rule is edited later.
             */
            $table->string('action_title');

            $table->text('action_description');

            $table->text('business_rationale')
                ->nullable();

            $table->string('business_impact');

            $table->string('effort');

            $table->unsignedSmallInteger('timeline_min_months')
                ->nullable();

            $table->unsignedSmallInteger('timeline_max_months')
                ->nullable();

            $table->decimal('investment_min', 12, 2)
                ->nullable();

            $table->decimal('investment_max', 12, 2)
                ->nullable();

            $table->string('currency', 3)
                ->default('USD');

            $table->unsignedInteger('dependency_order')
                ->default(1);

            $table->string('standard_reference')
                ->nullable();

            $table->timestamps();

            $table->unique(
                ['assessment_id', 'recommendation_rule_id'],
                'assessment_recommendation_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_recommendations');
    }
};