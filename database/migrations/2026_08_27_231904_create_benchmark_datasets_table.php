<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('benchmark_datasets', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('source');
            $table->integer('year');

            $table->string('etl_method')->default('Python ETL');

            $table->boolean('is_active')->default(false);

            $table->timestamp('imported_at')->nullable();

            $table->timestamps();

            $table->unique(['source', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('benchmark_datasets');
    }
};