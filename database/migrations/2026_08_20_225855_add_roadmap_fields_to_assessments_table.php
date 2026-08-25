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
    Schema::table('assessments', function (Blueprint $table) {
        $table->json('ai_roadmap')->nullable();
        $table->json('roadmap_inputs')->nullable();
        $table->timestamp('roadmap_generated_at')->nullable();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
   public function down(): void
{
    Schema::table('assessments', function (Blueprint $table) {
        $table->dropColumn([
            'ai_roadmap',
            'roadmap_inputs',
            'roadmap_generated_at',
        ]);
    });
}
};
