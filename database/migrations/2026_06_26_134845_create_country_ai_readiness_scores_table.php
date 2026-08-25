<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
{
    Schema::create('country_ai_readiness_scores', function (Blueprint $table) {
        $table->id();

        $table->string('country');
        $table->integer('year');

        $table->integer('final_rank')->nullable();
        $table->integer('official_rank')->nullable();

        $table->decimal('score', 8, 2);

        $table->string('score_type')->nullable();
        $table->integer('missing_values')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_ai_readiness_scores');
    }
};
