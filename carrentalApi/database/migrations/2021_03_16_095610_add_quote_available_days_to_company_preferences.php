<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuoteAvailableDaysToCompanyPreferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_preferences', function (Blueprint $table) {
            $table->integer('quote_available_days')->unsigned()->nullable();
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
            $table->dropColumn('quote_available_days');
        });
    }
}
