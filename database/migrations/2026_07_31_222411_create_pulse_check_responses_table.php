<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pulse_check_responses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pulse_check_id')
                ->constrained('pulse_checks')
                ->cascadeOnDelete();

            /*
             * We initially reuse selected questions and answer options
             * from the main assessment bank.
             */
            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            $table->foreignId('answer_option_id')
                ->constrained('answer_options')
                ->cascadeOnDelete();

            $table->timestamps();

            /*
             * Prevent the same Pulse Check from storing multiple answers
             * for the same question.
             */
            $table->unique(
                ['pulse_check_id', 'question_id'],
                'pulse_check_question_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pulse_check_responses');
    }
};