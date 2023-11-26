<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuoteIdToBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('booking_id');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->bigInteger('quote_id')->unsigned()->nullable();
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('cascade')->onUpdate('cascade');
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
            $table->bigInteger('booking_id')->unsigned()->nullable();
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_quote_id_foreign');
            $table->dropColumn('quote_id');
        });
    }
}
