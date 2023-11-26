<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('affiliates');

        Schema::create('agents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();

            $table->string('name')->index();
            $table->decimal('commission', 20, 2)->default('0.0');

            $table->bigInteger('contact_information_id')->unsigned()->nullable();

            $table->string('account_manager')->nullable();
            $table->string('referal_by')->nullable();

            $table->text('comments')->nullable();
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->foreign('contact_information_id')->references('id')->on('contact_information')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agents');
    }
}
