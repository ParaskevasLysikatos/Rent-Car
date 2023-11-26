<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompletedAtFieldToStatusAlteringTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('vehicles', function (Blueprint $table) {
        //     $table->string('status')->change();
        // });

        Schema::table('vehicle_statuses', function (Blueprint $table) {
            $table->dateTime('completed_at')->nullable()->index()->after('updated_at');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dateTime('completed_at')->nullable()->index()->after('updated_at');
        });

        Schema::table('transitions', function (Blueprint $table) {
            $table->dateTime('completed_at')->nullable()->index()->after('updated_at');
        });

        Schema::table('maintenances', function (Blueprint $table) {
            $table->dateTime('completed_at')->nullable()->index()->after('updated_at');

            $table->dropColumn('start');
            $table->dropColumn('end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_statuses', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });

        Schema::table('transitions', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });

        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropColumn('completed_at');

            $table->dateTime('start');
            $table->dateTime('end')->nullable();
        });

        // Schema::table('vehicles', function (Blueprint $table) {
        //     $table->string('status')->nullable()->change();
        // });
    }
}
