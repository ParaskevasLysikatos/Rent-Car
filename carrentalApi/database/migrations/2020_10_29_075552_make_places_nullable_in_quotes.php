<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakePlacesNullableInQuotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->bigInteger('checkin_place_id')->unsigned()->nullable()->change();
            $table->bigInteger('checkout_place_id')->unsigned()->nullable()->change();
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
            $table->bigInteger('checkin_place_id')->unsigned()->change();
            $table->bigInteger('checkout_place_id')->unsigned()->change();
        });
    }
}
