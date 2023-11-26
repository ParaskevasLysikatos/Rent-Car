<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_instances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->bigInteger('invoice_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('occupation')->nullable();
            $table->string('afm')->nullable();
            $table->string('doy')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('driver');
            $table->string('licence_number')->nullable();
            $table->string('licence_created')->nullable();
            $table->string('licence_expire')->nullable();
            $table->string('licence_country')->nullable();
            $table->string('identification_number')->nullable();
            $table->string('identification_created')->nullable();
            $table->string('identification_expire')->nullable();
            $table->string('identification_country')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('birth_place')->nullable();

            $table->string('rental_sequence_number');
            $table->string('booking_sequence_number')->nullable();
            $table->string('voucher_no')->nullable();
            $table->string('checkout_datetime')->nullable();
            $table->string('checkin_datetime')->nullable();
            $table->string('days')->nullable();
            $table->string('checkout_station')->nullable();
            $table->string('checkout_station_phone')->nullable();
            $table->string('licence_plate')->nullable();
            $table->string('group')->nullable();
            $table->string('vehicle_whole_model')->nullable();
            $table->string('checkout_km')->nullable();
            $table->string('checkin_km')->nullable();
            $table->string('driven_km')->nullable();
            $table->string('checkout_fuel_level')->nullable();
            $table->string('checkin_fuel_level')->nullable();

            $table->string('rental_agent')->nullable();
        });

        Schema::table('invoice_instances', function (Blueprint $table) {
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
        Schema::dropIfExists('invoice_instances');
    }
}
