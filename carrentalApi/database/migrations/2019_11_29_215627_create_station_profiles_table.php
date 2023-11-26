<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('language_id', 20);
            $table->bigInteger('station_id')->unsigned();

            $table->string('title');
            $table->text('description')->nullable();
        });

        Schema::table('station_profiles', function (Blueprint $table) {
            $table->foreign('station_id')->references('id')->on('stations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('station_profiles');
    }
}
