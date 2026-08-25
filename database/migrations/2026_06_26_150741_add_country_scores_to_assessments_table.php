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
        $table->decimal('company_score', 8, 2)->nullable();
        $table->decimal('country_ai_score', 8, 2)->nullable();
        $table->integer('country_ai_year')->nullable();
        $table->decimal('combined_score', 8, 2)->nullable();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assessments', function (Blueprint $table) {
            //
        });
    }
};
