<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVatIncludedToReservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->boolean('vat_included')->nullable();
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->boolean('vat_included')->nullable();
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->boolean('vat_included')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('vat_included');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('vat_included');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('vat_included');
        });
    }
}
