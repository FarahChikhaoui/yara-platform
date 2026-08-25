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
        Schema::table('dimensions', function (Blueprint $table) {
            Schema::table('dimensions', function (Blueprint $table) {
    $table->string('code')->nullable()->after('id');
    $table->decimal('weight', 5, 2)->nullable()->after('name');
});
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dimensions', function (Blueprint $table) {
                $table->dropColumn(['code', 'weight']);

        });
    }
};
