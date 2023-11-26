<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_preferences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('name');
            $table->string('job');
            $table->string('afm');
            $table->string('doi');
            $table->string('mite_number');
            $table->bigInteger('station_id')->unsigned();
            $table->bigInteger('place_id')->unsigned();
            $table->bigInteger('quote_source_id')->unsigned();
            $table->bigInteger('booking_source_id')->unsigned();
            $table->bigInteger('rental_source_id')->unsigned();
            $table->integer('checkin_free_seconds')->unsigned();
            $table->float('vat');
            $table->string('timezone');
            $table->string('quote_prefix');
            $table->string('booking_prefix');
            $table->string('rental_prefix');
            $table->string('invoice_prefix');
            $table->string('receipt_prefix');
            $table->string('payment_prefix');
            $table->string('pre_auth_prefix');
            $table->string('refund_prefix');
        });

        Schema::table('company_preferences', function (Blueprint $table) {
            $table->foreign('station_id')->references('id')->on('stations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('quote_source_id')->references('id')->on('booking_sources')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('booking_source_id')->references('id')->on('booking_sources')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('rental_source_id')->references('id')->on('booking_sources')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_preferences');
    }
}
