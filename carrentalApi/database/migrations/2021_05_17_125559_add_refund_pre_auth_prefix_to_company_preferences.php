<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundPreAuthPrefixToCompanyPreferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_preferences', function (Blueprint $table) {
            $table->string('refund_pre_auth_prefix')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_preferences', function (Blueprint $table) {
            $table->dropColumn('refund_pre_auth_prefix');
        });
    }
}
