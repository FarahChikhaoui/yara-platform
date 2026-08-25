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
        Schema::create('maturity_levels', function (Blueprint $table) {
              $table->id();

    $table->integer('level'); // 1, 2, 3, 4, 5
    $table->string('name');   // Initial, Developing, Defined...
    $table->integer('min_score');
    $table->integer('max_score');
    $table->text('description')->nullable();

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
        Schema::dropIfExists('maturity_levels');
    }
};
