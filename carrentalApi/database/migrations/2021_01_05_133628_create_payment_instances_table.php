<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_instances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();

            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('balance')->unsigned()->nullable();
            $table->decimal('amount', 2);
            $table->string('method')->nullable();
            $table->text('comments')->nullable();
            $table->string('reference')->nullable();
            $table->dateTime('payment_datetime');
            $table->bigInteger('station_id')->unsigned();
            $table->bigInteger('place_id')->unsigned()->nullable();
            $table->string('bank_transfer_account')->nullable();
            $table->string('cheque_due_date')->nullable();
            $table->string('cheque_number')->nullable();
            $table->string('credit_card_year')->nullable();
            $table->string('credit_card_month')->nullable();
            $table->string('credit_card_number')->nullable();
            $table->morphs('payer');
            $table->bigInteger('balance_id')->unsigned()->nullable();
            $table->string('place_text')->nullable();
            $table->bigInteger('invoice_id')->unsigned();
        });

        Schema::table('payment_instances', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('station_id')->references('id')->on('stations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('balance_id')->references('id')->on('balances')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_instances');
    }
}
