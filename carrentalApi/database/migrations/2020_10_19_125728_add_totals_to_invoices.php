<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalsToInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->float('sub_discount_total')->nullable();
            $table->float('fpa_perc')->nullable();
            $table->float('final_fpa')->nullable();
            $table->float('final_total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('sub_discount_total');
            $table->dropColumn('fpa_perc');
            $table->dropColumn('final_fpa');
            $table->dropColumn('final_total');
        });
    }
}
