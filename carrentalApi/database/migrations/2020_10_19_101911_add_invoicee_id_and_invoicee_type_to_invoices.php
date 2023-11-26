<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceeIdAndInvoiceeTypeToInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign('invoices_client_id_foreign');
            $table->dropColumn('client_id');
            $table->dropColumn('company_id');
            $table->morphs('invoicee');
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
            $table->dropMorphs('invoicee');
            $table->bigInteger('client_id')->unsigned()->nullable();
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->foreign('client_id')->references('id')->on('drivers')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
