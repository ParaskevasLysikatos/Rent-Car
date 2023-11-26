<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaceStationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('place_station', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('place_id')->unsigned();
            $table->bigInteger('station_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('place_station', function (Blueprint $table) {
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('station_id')->references('id')->on('stations')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('places', function (Blueprint $table) {
            $table->dropForeign('places_station_id_foreign');
            $table->dropColumn('station_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('place_station');
        Schema::table('places', function (Blueprint $table) {
            $table->bigInteger('station_id')->unsigned()->nullable();
        });
    }
}
