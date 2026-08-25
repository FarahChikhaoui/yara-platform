<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pulse_checks', function (Blueprint $table) {
            $table->id();

            /*
             * Anonymous identifier stored in the browser session or URL.
             * The visitor does not need an account.
             */
            $table->uuid('session_token')->unique();

            /*
             * Optional lead-capture information.
             * The visitor may complete the Pulse Check without providing it.
             */
            $table->string('email')->nullable();

            $table->enum('status', [
                'in_progress',
                'completed',
                'converted',
            ])->default('in_progress');

            /*
             * Simplified Pulse Check result.
             */
            $table->decimal('overall_score', 5, 2)->nullable();

            $table->string('readiness_signal')->nullable();

            /*
             * Store the strongest and weakest dimension IDs for fast display.
             */
            $table->foreignId('strongest_dimension_id')
                ->nullable()
                ->constrained('dimensions')
                ->nullOnDelete();

            $table->foreignId('weakest_dimension_id')
                ->nullable()
                ->constrained('dimensions')
                ->nullOnDelete();

            /*
             * If the visitor later registers, we can link the anonymous
             * Pulse Check to the newly created user.
             */
            $table->foreignId('converted_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pulse_checks');
    }
};