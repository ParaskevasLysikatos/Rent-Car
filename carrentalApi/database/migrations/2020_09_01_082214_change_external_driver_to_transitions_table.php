<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeExternalDriverToTransitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transitions', function (Blueprint $table) {
            $table->dropColumn('external_driver_name');
            $table->dropColumn('external_driver_document');
            $table->dropColumn('external_driver_licence');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transitions', function (Blueprint $table) {
            $table->string('external_driver_name')->nullable();
            $table->string('external_driver_document')->nullable();
            $table->string('external_driver_licence')->nullable();
        });
    }
}
