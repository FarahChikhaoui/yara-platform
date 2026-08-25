<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendation_rules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dimension_id')
                ->constrained('dimensions')
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->nullable()
                ->constrained('questions')
                ->nullOnDelete();

            /*
             * A rule can apply when the user selects an answer
             * at or below this score.
             *
             * Example:
             * max_answer_score = 2
             * means the rule applies to answers scored 1 or 2.
             */
            $table->unsignedTinyInteger('max_answer_score')
                ->default(2);

            $table->string('action_title');

            $table->text('action_description');

            $table->text('business_rationale')
                ->nullable();

            $table->enum('business_impact', [
                'Low',
                'Medium',
                'High',
                'Critical',
            ])->default('Medium');

            $table->enum('effort', [
                'Low',
                'Medium',
                'High',
            ])->default('Medium');

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

            $table->unsignedInteger('priority_weight')
                ->default(1);

            $table->unsignedInteger('dependency_order')
                ->default(1);

            $table->string('standard_reference')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->index([
                'dimension_id',
                'question_id',
                'max_answer_score',
                'is_active',
            ], 'recommendation_rules_lookup_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendation_rules');
    }
};