<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->string('payment_status')
                ->nullable()
                ->after('transformation_status');

            $table->string('stripe_checkout_session_id')
                ->nullable()
                ->after('payment_status');

            $table->timestamp('paid_at')
                ->nullable()
                ->after('stripe_checkout_session_id');
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'stripe_checkout_session_id',
                'paid_at',
            ]);
        });
    }
};