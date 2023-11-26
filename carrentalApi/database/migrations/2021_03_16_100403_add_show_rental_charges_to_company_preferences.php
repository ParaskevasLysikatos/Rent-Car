<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShowRentalChargesToCompanyPreferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_preferences', function (Blueprint $table) {
            $table->boolean('show_rental_charges')->nullable();
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
            $table->dropColumn('show_rental_charges');
        });
    }
}
