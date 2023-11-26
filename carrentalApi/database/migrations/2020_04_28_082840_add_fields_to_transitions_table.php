<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToTransitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transitions', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->unsignedBigInteger('type_id')->after('id');
        });

        Schema::table('transitions', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('transition_types')->onDelete('cascade')->onUpdate('cascade');
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
            $table->dropForeign('transitions_type_id_foreign');
            $table->dropColumn('type_id');
        });
    }
}
