<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodicFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodic_fees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->bigInteger('periodic_fee_type_id')->unsigned();
            $table->bigInteger('vehicle_id')->unsigned();

            $table->string('title');
            $table->text('description')->nullable();

            $table->decimal('fee', 20, 2)->default('0.0');

            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_expiration')->nullable();
            $table->dateTime('date_payed')->nullable();
        });

        Schema::table('periodic_fees', function (Blueprint $table) {
            $table->foreign('periodic_fee_type_id')->references('id')->on('periodic_fee_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periodic_fees');
    }
}
