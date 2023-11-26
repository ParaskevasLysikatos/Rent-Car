<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingIdToRentals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_rental_id_foreign');
            $table->dropColumn('rental_id');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->bigInteger('booking_id')->unsigned()->nullable();
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->bigInteger('rental_id')->unsigned()->nullable();
            $table->foreign('rental_id')->references('id')->on('rentals')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeign('rentals_booking_id_foreign');
            $table->dropColumn('booking_id');
        });
    }
}
