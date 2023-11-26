<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypesFieldToVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('color_title');
            $table->dropColumn('ownership');
            $table->dropColumn('use');
            $table->dropColumn('class');


            $table->unsignedBigInteger('transmission_type_id')->nullable()->after('id');
            $table->unsignedBigInteger('drive_type_id')->nullable()->after('id');
            $table->unsignedBigInteger('fuel_type_id')->nullable()->after('id');
            $table->unsignedBigInteger('ownership_type_id')->nullable()->after('id');
            $table->unsignedBigInteger('use_type_id')->nullable()->after('id');
            $table->unsignedBigInteger('class_type_id')->nullable()->after('id');
            $table->unsignedBigInteger('color_type_id')->nullable()->after('id');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreign('transmission_type_id')->references('id')->on('transmission_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('drive_type_id')->references('id')->on('drive_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('fuel_type_id')->references('id')->on('fuel_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ownership_type_id')->references('id')->on('ownership_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('use_type_id')->references('id')->on('use_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('class_type_id')->references('id')->on('class_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('color_type_id')->references('id')->on('color_types')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign('vehicles_transmission_type_id_foreign');
            $table->dropColumn('transmission_type_id');

            $table->dropForeign('vehicles_drive_type_id_foreign');
            $table->dropColumn('drive_type_id');

            $table->dropForeign('vehicles_fuel_type_id_foreign');
            $table->dropColumn('fuel_type_id');

            $table->dropForeign('vehicles_ownership_type_id_foreign');
            $table->dropColumn('ownership_type_id');

            $table->dropForeign('vehicles_use_type_id_foreign');
            $table->dropColumn('use_type_id');

            $table->dropForeign('vehicles_class_type_id_foreign');
            $table->dropColumn('class_type_id');

            $table->dropForeign('vehicles_color_type_id_foreign');
            $table->dropColumn('color_type_id');
        });
    }
}
