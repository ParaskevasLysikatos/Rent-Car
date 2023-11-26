<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricelistRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricelist_ranges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->bigInteger('pricelist_id')->unsigned();

            $table->decimal('cost', 20, 2);

            $table->smallInteger('minimum_days')->index();
            $table->smallInteger('maximum_days')->index();
        });

        Schema::table('pricelist_ranges', function (Blueprint $table) {
            $table->foreign('pricelist_id')->references('id')->on('pricelists')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricelist_ranges');
    }
}
