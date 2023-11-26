<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModificationNumberToBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->integer('modification_number')->unsigned()->default(0);
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('modification_number')->unsigned()->default(0);
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->integer('modification_number')->unsigned()->default(0);
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
            $table->dropColumn('modification_number');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('modification_number');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('modification_number');
        });
    }
}
