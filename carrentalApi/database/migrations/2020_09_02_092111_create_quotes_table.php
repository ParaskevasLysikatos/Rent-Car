<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->string('customer_text')->nullable();
            $table->bigInteger('booking_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->bigInteger('type_id')->unsigned()->nullable();
            $table->bigInteger('brand_id')->unsigned()->nullable();
            $table->bigInteger('agent_id')->unsigned()->nullable();
            $table->bigInteger('source_id')->unsigned()->nullable();
            $table->bigInteger('charge_type_id')->unsigned()->nullable();

            $table->string('status')->default('active')->index();

            $table->integer('duration')->index();
            $table->decimal('rate', 20, 2)->index();
            $table->decimal('discount_percentage', 5, 2)->default(0)->index();

            $table->dateTime('checkout_datetime');
            $table->bigInteger('checkout_station_id')->unsigned();
            $table->bigInteger('checkout_place_id')->unsigned();
            $table->decimal('checkout_station_fee', 20, 2)->default(0);
            $table->text('checkout_comments')->nullable();

            $table->dateTime('checkin_datetime');
            $table->bigInteger('checkin_station_id')->unsigned();
            $table->bigInteger('checkin_place_id')->unsigned();
            $table->decimal('checkin_station_fee', 20, 2)->default(0);
            $table->text('checkin_comments')->nullable();
            $table->boolean('may_extend')->default(FALSE)->index();

            $table->tinyInteger('estimated_km')->unsigned()->nullable();

            $table->dateTime('valid_date');

            $table->integer('distance')->unsigned()->default(0)->index();
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

            $table->text('comments')->nullable();
            $table->timestamps();
            $table->dateTime('completed_at')->nullable()->index();
            $table->softDeletes();
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('drivers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('charge_type_id')->references('id')->on('types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('source_id')->references('id')->on('booking_sources')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('checkout_station_id')->references('id')->on('stations')->onUpdate('cascade');
            $table->foreign('checkin_station_id')->references('id')->on('stations')->onUpdate('cascade');
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
        Schema::dropIfExists('quotes');
    }
}
