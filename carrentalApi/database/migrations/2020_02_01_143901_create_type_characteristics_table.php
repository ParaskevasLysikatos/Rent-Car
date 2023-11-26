<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeCharacteristicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_characteristics', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('type_id')->unsigned();
            $table->bigInteger('characteristic_id')->unsigned();

            $table->mediumInteger('ordering')->default(1000)->index();
        });

        Schema::table('type_characteristics', function (Blueprint $table) {
            $table->foreign('characteristic_id')->references('id')->on('characteristics')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_characteristics');
    }
}
