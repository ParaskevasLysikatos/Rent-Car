<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('bookings', function (Blueprint $table) {
            $table->bigInteger('type_id')->unsigned()->nullable()->after('user_id');
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
            $table->decimal('discount_percentage', 5, 2)->default(0)->index();

            $table->dateTime('checkout_datetime');
            $table->bigInteger('checkout_station_id')->unsigned();
            $table->decimal('checkout_station_fee', 20, 2)->default(0);
            $table->text('checkout_comments')->nullable();

            $table->dateTime('checkin_datetime');
            $table->bigInteger('checkin_station_id')->unsigned();
            $table->decimal('checkin_station_fee', 20, 2)->default(0);
            $table->text('checkin_comments')->nullable();

            $table->text('comments')->nullable();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('charge_type_id')->references('id')->on('types')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('source_id')->references('id')->on('booking_sources')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('contact_information_id')->references('id')->on('contact_information')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('checkout_station_id')->references('id')->on('stations')->onUpdate('cascade');
            $table->foreign('checkin_station_id')->references('id')->on('stations')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_type_id_foreign');
            $table->dropColumn('type_id');

            $table->dropForeign('bookings_charge_type_id_foreign');
            $table->dropColumn('charge_type_id');

            $table->dropForeign('bookings_agent_id_foreign');
            $table->dropColumn('agent_id');

            $table->dropForeign('bookings_source_id_foreign');
            $table->dropColumn('source_id');

            $table->dropForeign('bookings_contact_information_id_foreign');
            $table->dropColumn('contact_information_id');

            $table->dropForeign('bookings_checkout_station_id_foreign');
            $table->dropColumn('checkout_station_id');

            $table->dropForeign('bookings_checkin_station_id_foreign');
            $table->dropColumn('checkin_station_id');


            $table->dropColumn('duration');
            $table->dropColumn('rate');
            $table->dropColumn('distance');
            $table->dropColumn('extension_rate');
            $table->dropColumn('may_extend');
            $table->dropColumn('discount_percentage');

            $table->dropColumn('checkout_datetime');
            $table->dropColumn('checkout_station_fee');
            $table->dropColumn('checkout_comments');

            $table->dropColumn('checkin_datetime');
            $table->dropColumn('checkin_station_fee');
            $table->dropColumn('checkin_comments');

            $table->dropColumn('comments');
        });
    }
}
