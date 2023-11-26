<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancelReasonIdToReservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->bigInteger('cancel_reason_id')->unsigned()->nullable();
            $table->foreign('cancel_reason_id')->references('id')->on('cancel_reasons')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->bigInteger('cancel_reason_id')->unsigned()->nullable();
            $table->foreign('cancel_reason_id')->references('id')->on('cancel_reasons')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->bigInteger('cancel_reason_id')->unsigned()->nullable();
            $table->foreign('cancel_reason_id')->references('id')->on('cancel_reasons')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign('quotes_cancel_reason_id_foreign');
            $table->dropColumn('cancel_reason_id');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_cancel_reason_id_foreign');
            $table->dropColumn('cancel_reason_id');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeign('rentals_cancel_reason_id_foreign');
            $table->dropColumn('cancel_reason_id');
        });
    }
}
