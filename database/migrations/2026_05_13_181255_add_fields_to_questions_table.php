<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
  public function up(): void
{
    Schema::table('questions', function (Blueprint $table) {
        if (!Schema::hasColumn('questions', 'weight')) {
            $table->integer('weight')->default(1);
        }

        if (!Schema::hasColumn('questions', 'help_text')) {
            $table->text('help_text')->nullable();
        }

        if (!Schema::hasColumn('questions', 'is_active')) {
            $table->boolean('is_active')->default(true);
        }
    });
}


    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {

            $table->dropColumn([
                'weight',
                'help_text',
                'is_active'
            ]);

        });
    }
};