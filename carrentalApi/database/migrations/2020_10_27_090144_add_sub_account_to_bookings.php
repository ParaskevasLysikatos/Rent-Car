<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubAccountToBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign('quotes_sub_agent_id_foreign');
            $table->dropColumn('sub_agent_id');
            $table->nullableMorphs('sub_account');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_sub_agent_id_foreign');
            $table->dropColumn('sub_agent_id');
            $table->nullableMorphs('sub_account');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeign('rentals_sub_agent_id_foreign');
            $table->dropColumn('sub_agent_id');
            $table->nullableMorphs('sub_account');
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
            $table->dropColumn('sub_account_id');
            $table->dropColumn('sub_account_type');
            $table->bigInteger('sub_agent_id')->unsigned()->nullable();
            $table->foreign('sub_agent_id')->references('id')->on('agents')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('sub_account_id');
            $table->dropColumn('sub_account_type');
            $table->bigInteger('sub_agent_id')->unsigned()->nullable();
            $table->foreign('sub_agent_id')->references('id')->on('agents')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('sub_account_id');
            $table->dropColumn('sub_account_type');
            $table->bigInteger('sub_agent_id')->unsigned()->nullable();
            $table->foreign('sub_agent_id')->references('id')->on('agents')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
