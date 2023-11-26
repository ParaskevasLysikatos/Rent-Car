<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRentalToBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->float('rental_fee');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->float('rental_fee');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->float('rental_fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('rental_fee');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('rental_fee');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('rental_fee');
        });
    }
}
