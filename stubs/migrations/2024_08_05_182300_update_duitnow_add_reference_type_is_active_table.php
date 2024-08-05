<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('duitnow_transactions', function (Blueprint $table) {
            $table->string('reference_type', 100)->nullable()->after('reference_id');
        });

        Schema::table('duitnow_banks', function (Blueprint $table) {
            $table->boolean('is_active')->nullable()->default(true)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('duitnow_transactions', function (Blueprint $table) {
            $table->dropColumn('reference_type');
        });
        
        Schema::table('duitnow_banks', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
