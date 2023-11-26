<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlaceText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('place_text')->nullable();
        });
        Schema::table('transitions', function (Blueprint $table) {
            $table->string('co_place_text')->nullable();
            $table->string('ci_place_text')->nullable();
        });
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('checkout_place_text')->nullable();
            $table->string('checkin_place_text')->nullable();
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('checkout_place_text')->nullable();
            $table->string('checkin_place_text')->nullable();
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->string('checkout_place_text')->nullable();
            $table->string('checkin_place_text')->nullable();
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
            $table->dropColumn('place_text');
        });
        Schema::table('transitions', function (Blueprint $table) {
            $table->dropColumn('co_place_text');
            $table->dropColumn('ci_place_text');
        });
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('checkout_place_text');
            $table->dropColumn('checkin_place_text');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('checkout_place_text');
            $table->dropColumn('checkin_place_text');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('checkout_place_text');
            $table->dropColumn('checkin_place_text');
        });
    }
}
