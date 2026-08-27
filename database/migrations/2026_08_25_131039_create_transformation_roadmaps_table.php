<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transformation_roadmaps', function (Blueprint $table) {
            $table->id();

            // One roadmap belongs to one transformation assessment.
            $table->foreignId('assessment_id')
                ->constrained()
                ->cascadeOnDelete()
                ->unique();

            // Consultant responsible for the roadmap.
            $table->foreignId('consultant_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // draft -> ready
            $table->string('status')->default('draft');

            // Consultant's overall expert guidance.
            $table->text('consultant_notes')->nullable();

            // Overall risks / dependencies identified during review.
            $table->text('risks_dependencies')->nullable();

            // Useful later when AI generation is connected.
            $table->timestamp('generated_at')->nullable();

            // Set when consultant approves the roadmap for the client.
            $table->timestamp('finalized_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transformation_roadmaps');
    }
};