<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsToTransitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transitions', function (Blueprint $table) {
            //Drop old foreing keys
            $table->dropForeign('transitions_station_id_from_foreign');
            $table->dropColumn('station_id_from');
            $table->dropForeign('transitions_station_id_to_foreign');
            $table->dropColumn('station_id_to');

            $table->unsignedBigInteger('user_id')->nullable()->change();

            //if is selected external party then add driver name and ignore driver_id
            $table->boolean('external_party')->nullable();
            $table->string('external_driver_name')->nullable();

            //Data about check out
            $table->dateTime('co_datetime');
            $table->unsignedBigInteger('co_station_id');
            $table->unsignedBigInteger('co_place_id')->nullable();
            $table->string('co_notes')->nullable();
            $table->float('co_km');
            $table->tinyInteger('co_fuel_level');
            $table->unsignedBigInteger('co_user_id');

            //Data about check in
            $table->dateTime('ci_datetime');
            $table->unsignedBigInteger('ci_station_id');
            $table->unsignedBigInteger('ci_place_id')->nullable();
            $table->string('ci_notes')->nullable();
            $table->float('ci_km')->nullable();
            $table->tinyInteger('ci_fuel_level')->nullable();
            $table->unsignedBigInteger('ci_user_id')->nullable();

            //Transition Notes
            $table->string('notes')->nullable();
        });

        Schema::table('transitions', function (Blueprint $table) {

            $table->foreign('co_station_id')->references('id')->on('stations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('co_place_id')->references('id')->on('places')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('co_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('ci_station_id')->references('id')->on('stations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ci_place_id')->references('id')->on('places')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ci_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

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
            $table->dropForeign('transitions_co_station_id_foreign');
            $table->dropForeign('transitions_co_place_id_foreign');
            $table->dropForeign('transitions_co_user_id_foreign');

            $table->dropForeign('transitions_ci_station_id_foreign');
            $table->dropForeign('transitions_ci_place_id_foreign');
            $table->dropForeign('transitions_ci_user_id_foreign');

            $table->dropColumn('external_party');
            $table->dropColumn('external_driver_name');

            $table->dropColumn('co_datetime');
            $table->dropColumn('co_station_id');
            $table->dropColumn('co_place_id');
            $table->dropColumn('co_notes');
            $table->dropColumn('co_km');
            $table->dropColumn('co_fuel_level');
            $table->dropColumn('co_user_id');

            $table->dropColumn('ci_datetime');
            $table->dropColumn('ci_station_id');
            $table->dropColumn('ci_place_id');
            $table->dropColumn('ci_notes');
            $table->dropColumn('ci_km');
            $table->dropColumn('ci_fuel_level');
            $table->dropColumn('ci_user_id');

            $table->dropColumn('notes');
        });
    }
}
