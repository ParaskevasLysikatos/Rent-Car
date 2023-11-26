<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_exchanges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('driver_id')->unsigned()->nullable();
            $table->bigInteger('old_vehicle_id')->unsigned();
            $table->bigInteger('old_vehicle_transition_id')->unsigned()->nullable();
            $table->bigInteger('new_vehicle_type_id')->unsigned()->nullable();
            $table->bigInteger('new_vehicle_id')->unsigned()->nullable();
            $table->bigInteger('new_vehicle_transition_id')->unsigned()->nullable();
            $table->bigInteger('rental_id')->unsigned();
            $table->dateTime('datetime')->nullable();
            $table->bigInteger('place_id')->unsigned()->nullable();
            $table->string('place_text')->nullable();
            $table->string('reason');
            $table->string('status')->default('pending')->nullable();
            $table->timestamps();
        });

        Schema::table('vehicle_exchanges', function (Blueprint $table) {
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('old_vehicle_id')->references('id')->on('vehicles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('old_vehicle_transition_id')->references('id')->on('transitions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('new_vehicle_type_id')->references('id')->on('types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('new_vehicle_id')->references('id')->on('vehicles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('new_vehicle_transition_id')->references('id')->on('transitions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('rental_id')->references('id')->on('rentals')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_exchanges');
    }
}
