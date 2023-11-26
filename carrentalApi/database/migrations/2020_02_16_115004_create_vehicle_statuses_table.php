<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('status')->index();

            $table->bigInteger('vehicle_id')->unsigned();
            $table->bigInteger('user_id')->nullable()->unsigned();

            $table->bigInteger('booking_id')->nullable()->unsigned();
            $table->bigInteger('transition_id')->nullable()->unsigned();
            $table->bigInteger('maintenance_id')->nullable()->unsigned();
        });

        Schema::table('vehicle_statuses', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('transition_id')->references('id')->on('transitions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('maintenance_id')->references('id')->on('maintenances')->onDelete('cascade')->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_statuses');
    }
}
