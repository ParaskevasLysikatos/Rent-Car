<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgentVoucherToBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('agent_voucher')->nullable();
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('agent_voucher')->nullable();
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->string('agent_voucher')->nullable();
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
            $table->dropColumn('agent_voucher');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('agent_voucher');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('agent_voucher');
        });
    }
}
