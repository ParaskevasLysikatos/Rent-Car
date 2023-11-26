<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceVisitDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_visit_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('service_visit_id')->unsigned();
            $table->bigInteger('service_details_id')->unsigned();
            $table->bigInteger('service_status_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('service_visit_details', function (Blueprint $table) {
            $table->foreign('service_visit_id')->references('id')->on('service_visit')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('service_details_id')->references('id')->on('service_details')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('service_status_id')->references('id')->on('service_status')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_visit_details');
    }
}
