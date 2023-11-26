<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlightFieldToBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('flight')->nullable();
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('flight')->nullable();
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->string('flight')->nullable();
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
            $table->dropColumn('flight');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('flight');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('flight');
        });
    }
}
