<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProgramIdToBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->bigInteger('program_id')->unsigned()->nullable()->after('source_id');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->bigInteger('program_id')->unsigned()->nullable()->after('source_id');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->bigInteger('program_id')->unsigned()->nullable()->after('source_id');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade')->onUpdate('cascade');
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
            $table->dropForeign('quotes_program_id_foreign');
            $table->dropColumn('program_id');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_program_id_foreign');
            $table->dropColumn('program_id');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeign('rentals_program_id_foreign');
            $table->dropColumn('program_id');
        });
    }
}
