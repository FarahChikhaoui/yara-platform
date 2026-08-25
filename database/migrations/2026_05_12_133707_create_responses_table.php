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
    public function up()
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->id();

    $table->foreignId('assessment_id')
          ->constrained()
          ->onDelete('cascade');

    $table->foreignId('question_id')
          ->constrained()
          ->onDelete('cascade');

    $table->foreignId('answer_option_id')
          ->nullable()
          ->constrained()
          ->onDelete('set null');

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
        Schema::dropIfExists('responses');
    }
};
