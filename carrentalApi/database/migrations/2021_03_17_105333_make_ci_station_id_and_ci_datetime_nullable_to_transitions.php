<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeCiStationIdAndCiDatetimeNullableToTransitions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transitions', function (Blueprint $table) {
            $table->unsignedBigInteger('ci_station_id')->nullable()->change();
            $table->dateTime('ci_datetime')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transitions', function (Blueprint $table) {
            //
        });
    }
}
