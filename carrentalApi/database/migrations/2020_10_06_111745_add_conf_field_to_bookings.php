<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConfFieldToBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('confirmation_number')->nullable();
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('confirmation_number')->nullable();
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->string('confirmation_number')->nullable();
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
            $table->dropColumn('confirmation_number');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('confirmation_number');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('confirmation_number');
        });
    }
}
