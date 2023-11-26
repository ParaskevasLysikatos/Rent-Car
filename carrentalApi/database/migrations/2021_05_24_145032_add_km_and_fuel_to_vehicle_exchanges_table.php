<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKmAndFuelToVehicleExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_exchanges', function (Blueprint $table) {
            $table->float('new_vehicle_rental_co_km')->nullable();
            $table->tinyInteger('new_vehicle_rental_co_fuel_level')->nullable();
            $table->float('old_vehicle_rental_co_km')->nullable();
            $table->tinyInteger('old_vehicle_rental_co_fuel_level')->nullable();
            $table->float('old_vehicle_rental_ci_km')->nullable();
            $table->tinyInteger('old_vehicle_rental_ci_fuel_level')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_exchanges', function (Blueprint $table) {
            $table->dropColumn('new_vehicle_rental_co_km');
            $table->dropColumn('new_vehicle_rental_co_fuel_level');
            $table->dropColumn('old_vehicle_rental_co_km');
            $table->dropColumn('old_vehicle_rental_co_fuel_level');
            $table->dropColumn('old_vehicle_rental_ci_km');
            $table->dropColumn('old_vehicle_rental_ci_fuel_level');
        });
    }
}
