<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExcessToBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->float('excess')->nullable();
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->float('excess')->nullable();
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->float('excess')->nullable();
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
            $table->dropColumn('excess');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('excess');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('excess');
        });
    }
}
