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
        $table->text('consultant_notes')->nullable();
        $table->string('review_status')->default('awaiting_review');
        $table->unsignedBigInteger('reviewed_by')->nullable();
        $table->timestamp('reviewed_at')->nullable();

        $table->foreign('reviewed_by')
            ->references('id')
            ->on('users')
            ->nullOnDelete();
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
        $table->dropForeign(['reviewed_by']);

        $table->dropColumn([
            'consultant_notes',
            'review_status',
            'reviewed_by',
            'reviewed_at',
        ]);
    });
}
};
