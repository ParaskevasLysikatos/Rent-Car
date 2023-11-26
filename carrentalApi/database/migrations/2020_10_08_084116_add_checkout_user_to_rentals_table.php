<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckoutUserToRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('checkout_driver_id');
            $table->dropColumn('checkin_driver_id');
            $table->bigInteger('checkout_user_id')->unsigned()->nullable();
            $table->bigInteger('checkin_user_id')->unsigned()->nullable();
            $table->foreign('checkout_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('checkin_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeign('rentals_checkout_user_id_foreign');
            $table->dropForeign('rentals_checkin_user_id_foreign');
            $table->dropColumn('checkout_user_id');
            $table->dropColumn('checkin_user_id');
            $table->bigInteger('checkout_driver_id')->unsigned()->nullable();
            $table->bigInteger('checkin_driver_id')->unsigned()->nullable();
        });
    }
}
