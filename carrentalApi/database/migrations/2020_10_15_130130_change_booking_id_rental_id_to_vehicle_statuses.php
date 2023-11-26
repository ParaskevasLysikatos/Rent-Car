<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBookingIdRentalIdToVehicleStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_statuses', function (Blueprint $table) {
            $table->dropForeign('vehicle_statuses_booking_id_foreign');
            $table->dropColumn('booking_id');
            $table->bigInteger('rental_id')->unsigned()->nullable();
            $table->foreign('rental_id')->references('id')->on('rentals')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_statuses', function (Blueprint $table) {
            $table->dropForeign('vehicle_statuses_rental_id_foreign');
            $table->dropColumn('rental_id');
            $table->bigInteger('booking_id')->unsigned()->nullable();
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
