<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFieldsToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->nullable()->change();
            $table->unsignedBigInteger('balance')->nullable()->after('booking_id');
            $table->string('reference')->nullable();
            $table->string('method')->default('cache')->change();
            $table->dateTime('payment_datetime');

            $table->unsignedBigInteger('station_id');
            $table->unsignedBigInteger('place_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('station_id')->references('id')->on('stations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('balance');
            $table->dropColumn('reference');
            $table->dropColumn('payment_datetime');

            $table->dropForeign('payments_station_id_foreign');
            $table->dropColumn('station_id');

            $table->dropForeign('payments_place_id_foreign');
            $table->dropColumn('place_id');

            $table->dropForeign('payments_driver_id_foreign');
            $table->dropColumn('driver_id');
        });
    }
}
