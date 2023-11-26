<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraDayFieldToBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->tinyInteger('extra_day')->default(0);
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->tinyInteger('extra_day')->default(0);
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->tinyInteger('extra_day')->default(0);
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
            $table->dropColumn('extra_day');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('extra_day');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('extra_day');
        });
    }
}
