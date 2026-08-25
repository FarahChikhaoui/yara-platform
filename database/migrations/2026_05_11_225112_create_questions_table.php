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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

    $table->foreignId('dimension_id')
          ->constrained()
          ->onDelete('cascade');

    $table->text('question_text');

    $table->integer('weight')->default(1);

    $table->text('help_text')->nullable();

    $table->boolean('is_active')->default(true);

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
        Schema::dropIfExists('questions');
    }
};
