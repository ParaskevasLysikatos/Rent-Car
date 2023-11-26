<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->dateTime('transaction_datetime')->default(DB::raw('NOW()'));
            $table->float('debit')->default(0);
            $table->bigInteger('rental_id')->unsigned()->nullable();
            $table->bigInteger('booking_id')->unsigned()->nullable();
            $table->nullableMorphs('transactor');
            $table->bigInteger('invoice_id')->unsigned()->nullable();
            $table->bigInteger('balance_id')->unsigned();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('rental_id')->references('id')->on('rentals')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('balance_id')->references('id')->on('balances')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
