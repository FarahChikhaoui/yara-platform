<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roadmap_initiatives', function (Blueprint $table) {
            $table->id();

            $table->foreignId('transformation_roadmap_id')
                ->constrained('transformation_roadmaps')
                ->cascadeOnDelete();

            // Initiative content
            $table->string('title');
            $table->text('description');

            // Assessment dimension this initiative addresses.
            $table->string('dimension')->nullable();

            // high / medium / low
            $table->string('priority')->default('medium');

            // Example: 0-30 days, 30-60 days, 3-6 months
            $table->string('phase')->nullable();

            // low / medium / high
            $table->string('effort')->nullable();

            // low / medium / high
            $table->string('impact')->nullable();

            // Consultant can add implementation-specific guidance.
            $table->text('consultant_guidance')->nullable();

            // Allows us to reorder initiatives in the roadmap.
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roadmap_initiatives');
    }
};