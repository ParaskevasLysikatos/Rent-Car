<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRentalCheckinValuesToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dateTime('checkin_datetime')->nullable()->change();
            $table->bigInteger('checkin_station_id')->unsigned()->nullable()->change();
            $table->bigInteger('checkin_driver_id')->unsigned()->nullable()->change();
            $table->bigInteger('checkin_km')->unsigned()->nullable()->change();
            $table->integer('checkin_fuel_level')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dateTime('checkin_datetime')->change();
            $table->bigInteger('checkin_station_id')->unsigned()->change();
            $table->bigInteger('checkin_driver_id')->unsigned()->change();
            $table->bigInteger('checkin_km')->unsigned()->change();
            $table->integer('checkin_fuel_level')->unsigned()->change();
        });
    }
}
