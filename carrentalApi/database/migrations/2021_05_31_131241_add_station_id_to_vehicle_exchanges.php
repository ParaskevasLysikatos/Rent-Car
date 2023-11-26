<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStationIdToVehicleExchanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_exchanges', function (Blueprint $table) {
            $table->bigInteger('station_id')->unsigned()->nullable();
            $table->foreign('station_id')->references('id')->on('stations')->onDelete('cascade')->onUpdate('cascade');
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
            $table->dropForeign('vehicle_exchanges_station_id_foreign');
            $table->dropColumn('station_id');
        });
    }
}
