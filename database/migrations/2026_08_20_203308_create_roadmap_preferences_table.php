<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roadmap_preferences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assessment_id')
                ->constrained()
                ->onDelete('cascade');

            // Desired maturity level the organization wants to reach
            $table->string('target_maturity');

            // Desired implementation horizon
            $table->string('timeframe');

            // Budget capacity rather than an invented exact amount
            $table->string('budget_level');

            // Selected business / AI priorities
            $table->json('strategic_priorities');

            // Optional organization-specific limitations
            $table->text('constraints')->nullable();

            $table->timestamps();

            // One planning configuration per assessment
            $table->unique('assessment_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('roadmap_preferences');
    }
};