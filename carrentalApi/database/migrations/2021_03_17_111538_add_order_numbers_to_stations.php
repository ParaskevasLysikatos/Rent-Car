<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderNumbersToStations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stations', function (Blueprint $table) {
            $table->integer('quote_number')->unsigned()->default(0);
            $table->integer('booking_number')->unsigned()->default(0);
            $table->integer('rental_number')->unsigned()->default(0);
            $table->integer('invoice_number')->unsigned()->default(0);
            $table->integer('receipt_number')->unsigned()->default(0);
            $table->integer('payment_number')->unsigned()->default(0);
            $table->integer('refund_number')->unsigned()->default(0);
            $table->integer('pre_auth_number')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stations', function (Blueprint $table) {
            $table->dropColumn('quote_number');
            $table->dropColumn('booking_number');
            $table->dropColumn('rental_number');
            $table->dropColumn('invoice_number');
            $table->dropColumn('receipt_number');
            $table->dropColumn('payment_number');
            $table->dropColumn('refund_number');
            $table->dropColumn('pre_auth_number');
        });
    }
}
