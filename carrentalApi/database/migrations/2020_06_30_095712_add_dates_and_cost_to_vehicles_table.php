<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatesAndCostToVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->double('depreciation_rate')->nullable();
            $table->double('depreciation_rate_year')->nullable();
            $table->double('sale_amount')->nullable();
            $table->date('sale_date')->nullable();
            $table->boolean('start_stop')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('depreciation_rate');
            $table->dropColumn('depreciation_rate_year');
            $table->dropColumn('sale_amount');
            $table->dropColumn('sale_date');
            $table->dropColumn('start_stop');
        });
    }
}
