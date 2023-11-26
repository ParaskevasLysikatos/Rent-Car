<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('id');
            $table->unsignedBigInteger('checkout_place_id')->nullable()->after('checkout_station_id');
            $table->unsignedBigInteger('checkin_place_id')->nullable()->after('checkin_station_id');
        });
        Schema::table('bookings', function (Blueprint $table) {
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
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_company_id_foreign');
            $table->dropColumn('company_id');

            $table->dropForeign('bookings_checkout_place_id_foreign');
            $table->dropColumn('checkout_place_id');

            $table->dropForeign('bookings_checkin_place_id_foreign');
            $table->dropColumn('checkin_place_id');
        });
    }
}
