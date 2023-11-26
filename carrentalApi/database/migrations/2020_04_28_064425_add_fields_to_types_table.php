<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('types', function (Blueprint $table) {
            $table->unsignedBigInteger('min_category')->nullable();
            $table->unsignedBigInteger('max_category')->nullable();
        });

        Schema::table('types', function (Blueprint $table) {
            $table->foreign('min_category')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('max_category')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('types', function (Blueprint $table) {
            $table->dropForeign('types_min_category_foreign');
            $table->dropColumn('min_category');
            $table->dropForeign('types_max_category_foreign');
            $table->dropColumn('max_category');
        });
    }
}
