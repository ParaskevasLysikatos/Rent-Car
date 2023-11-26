<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactIdToBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->bigInteger('contact_id')->unsigned()->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->bigInteger('contact_id')->unsigned()->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->bigInteger('contact_id')->unsigned()->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade')->onUpdate('cascade');
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
            $table->dropForeign('quotes_contact_id_foreign');
            $table->dropColumn('contact_id');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_contact_id_foreign');
            $table->dropColumn('contact_id');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeign('rentals_contact_id_foreign');
            $table->dropColumn('contact_id');
        });
    }
}
