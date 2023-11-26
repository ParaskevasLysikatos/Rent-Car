<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrefixToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->bigInteger('number')->unsigned()->nullable();
            $table->string('sequence_number')->nullable();
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->bigInteger('number')->unsigned()->nullable();
            $table->string('sequence_number')->nullable();
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->bigInteger('number')->unsigned()->nullable();
            $table->string('sequence_number')->nullable();
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('sequence_number')->nullable();
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->bigInteger('number')->unsigned()->nullable();
            $table->string('sequence_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
}
