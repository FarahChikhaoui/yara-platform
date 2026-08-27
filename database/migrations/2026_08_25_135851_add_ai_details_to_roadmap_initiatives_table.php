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
    Schema::table('roadmap_initiatives', function (Blueprint $table) {

        // Explains why this initiative was selected
        // based on the assessment evidence.
        $table->text('business_rationale')
            ->nullable()
            ->after('description');

        // AI-estimated portion of the client's TOTAL roadmap budget.
        // Example: "$5,000 - $10,000"
        $table->string('investment')
            ->nullable()
            ->after('impact');

        // Optional relevant framework/standard.
        // Example: "ISO/IEC 42001"
        $table->string('standard_reference')
            ->nullable()
            ->after('investment');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
{
    Schema::table('roadmap_initiatives', function (Blueprint $table) {
        $table->dropColumn([
            'business_rationale',
            'investment',
            'standard_reference',
        ]);
    });
}
};
