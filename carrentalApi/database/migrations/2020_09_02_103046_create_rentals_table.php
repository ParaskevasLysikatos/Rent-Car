<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();

            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->bigInteger('type_id')->unsigned()->nullable();
            $table->bigInteger('brand_id')->unsigned()->nullable();
            $table->bigInteger('vehicle_id')->nullable()->unsigned();

            $table->bigInteger('charge_type_id')->unsigned()->nullable();
            $table->bigInteger('contact_information_id')->unsigned()->nullable();
            $table->bigInteger('agent_id')->unsigned()->nullable();
            $table->bigInteger('source_id')->unsigned()->nullable();

            $table->string('status')->default('pending')->index();

            $table->integer('duration')->index();

            $table->decimal('rate', 20, 2)->index();
            $table->integer('distance')->unsigned()->nullable()->index();
            $table->decimal('extension_rate', 20, 2)->nullable()->index();
            $table->boolean('may_extend')->default(FALSE)->index();

            $table->dateTime('checkout_datetime');
            $table->bigInteger('checkout_station_id')->unsigned();
            $table->bigInteger('checkout_place_id')->unsigned()->nullable();
            $table->decimal('checkout_station_fee', 20, 2)->default(0);
            $table->text('checkout_comments')->nullable();

            $table->dateTime('checkin_datetime');
            $table->bigInteger('checkin_station_id')->unsigned();
            $table->bigInteger('checkin_place_id')->unsigned()->nullable();
            $table->decimal('checkin_station_fee', 20, 2)->default(0);
            $table->text('checkin_comments')->nullable();

            $table->integer('distance')->unsigned()->default(0)->change();
            $table->decimal('distance_rate', 20, 2)->default('0.0')->index();

            $table->decimal('transport_fee', 20, 2)->default('0.0')->index();
            $table->decimal('insurance_fee', 20, 2)->default('0.0')->index();
            $table->decimal('options_fee', 20, 2)->default('0.0')->index();

            $table->decimal('fuel_fee', 20, 2)->default('0.0')->index();
            $table->decimal('subcharges_fee', 20, 2)->default('0.0')->index();

            $table->decimal('discount', 6, 2)->default('0.0')->index();
            $table->decimal('voucher', 20, 2)->default('0.0')->index();

            $table->decimal('total', 20, 2)->default('0.0')->index();
            $table->decimal('total_net', 20, 2)->default('0.0')->index();
            $table->decimal('total_paid', 20, 2)->default('0.0')->index();
            $table->decimal('vat', 6, 2)->default('24.0')->index();

            $table->decimal('balance', 20, 2)->default('0.0')->index();

            $table->bigInteger('checkout_driver_id')->unsigned();
            $table->bigInteger('checkout_km')->unsigned();
            $table->tinyInteger('checkout_fuel_level')->unsigned();

            $table->bigInteger('checkin_driver_id')->unsigned();
            $table->bigInteger('checkin_km')->unsigned();
            $table->tinyInteger('checkin_fuel_level')->unsigned();

            $table->text('comments')->nullable();
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('charge_type_id')->references('id')->on('types')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('source_id')->references('id')->on('booking_sources')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('contact_information_id')->references('id')->on('contact_information')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('checkout_station_id')->references('id')->on('stations')->onUpdate('cascade');
            $table->foreign('checkin_station_id')->references('id')->on('stations')->onUpdate('cascade');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('checkout_place_id')->references('id')->on('places')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('checkin_place_id')->references('id')->on('places')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rentals');
    }
}
