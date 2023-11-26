<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrintingFieldsToCompanyPreferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_preferences', function (Blueprint $table) {
            $table->text('rental_rate_terms')->nullable();
            $table->text('rental_vehicle_condition')->nullable();
            $table->text('rental_gdpr')->nullable();
            $table->text('invoice_first_box')->nullable();
            $table->text('invoice_second_box')->nullable();
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
            $table->dropColumn('rental_rate_terms');
            $table->dropColumn('rental_vehicle_condition');
            $table->dropColumn('rental_gdpr');
            $table->dropColumn('invoice_first_box');
            $table->dropColumn('invoice_second_box');
        });
    }
}
