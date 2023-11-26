<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();

            $table->bigInteger('type_id')->unsigned();

            $table->string('vin')->unique();

            $table->string('make')->index();
            $table->string('model')->index();
            $table->string('variant')->nullable();

            $table->string('engine')->index();
            $table->string('power')->index();
            $table->string('fuel_type')->default('Gasoline')->index();
            $table->string('drive_type')->default('FWD')->index();
            $table->string('transmission')->default('manual')->index();
            $table->smallInteger('doors')->nullable()->unsigned()->index();
            $table->smallInteger('seats')->nullable()->unsigned()->index();
            $table->string('euroclass')->nullable()->index();
        });

        Schema::table('vehicles', function (Blueprint $table) {
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
        Schema::dropIfExists('vehicles');
    }
}
