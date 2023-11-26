<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('type')->default('invoice');
            $table->string('range')->nullable();
            $table->unsignedBigInteger('number');
            $table->date('date');
            $table->float('fpa')->default(24);
            $table->float('discount')->nullable();
            $table->date('datePayed')->nullable();
            $table->string('notes')->nullable();
            $table->string('payment_way')->default('Τοις μετρητοίς');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('drivers')->onDelete('cascade')->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
