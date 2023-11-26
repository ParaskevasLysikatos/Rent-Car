<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewDataToVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            //Διεθνής κωδικός – ACRISS
            $table->string('international_code')->nullable();
            //Χρώμα τίτλος (Λατινικά Value)
            $table->string('color_title')->nullable();
            //Χρώμα κωδικός
            $table->string('color_code')->nullable();
            //Warranty Expiration
            $table->date('warranty_expiration')->nullable();
            //Engine Number
            $table->string('engine_number')->nullable();
            //Ντεπόζιτο
            $table->string('tank')->nullable();
            //Ρύποι
            $table->string('pollution')->nullable();
            //Manufactured Year
            $table->year('manufactured_year')->nullable();
            //Radio code - keys
            $table->string('radio_code')->nullable();
            //Ημερομηνία αγοράς
            $table->date('purchase_date')->nullable();
            //Ποσό αγοράς
            $table->bigInteger('purchase_amount')->nullable();
            //Ημερομηνία Buy Back
            $table->date('buy_back')->nullable();
            //Ημερομηνία έκδοσης της πρώτης άδειας κυκλοφορίας
            $table->date('first_date_marketing_authorisation')->nullable();
            //Ημερομηνία πρώτης αδείας στην Ελλάδα
            $table->date('first_date_marketing_authorisation_gr')->nullable();
            //Ημερομηνία εξαγωγής στον στόλο μας
            $table->date('import_to_system')->nullable();
            //Ημερομηνία εξαγωγής στον στόλο μας
            $table->date('export_from_system')->nullable();
            //Ημερομηνία εξαγωγής - πρόβλεψη
            $table->date('forecast_export_from_system')->nullable();
            //Ιδιοκτησία
            $table->string('ownership')->nullable();
            //Χρήση
            $table->string('use')->nullable();
            //Κλάση - Ομάδα (mini / economy / compact / full size / luxury )
            $table->string('class')->nullable();
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
////            $table->dropColumn('international_code');
////            $table->dropColumn('color_title');
////            $table->dropColumn('color_code');
////            $table->dropColumn('warranty_expiration');
////            $table->dropColumn('engine_number');
////            $table->dropColumn('tank');
////            $table->dropColumn('pollution');
////            $table->dropColumn('manufactured_year');
////            $table->dropColumn('radio_code');
////            $table->dropColumn('purchase_date');
////            $table->dropColumn('purchase_amount');
////            $table->dropColumn('buy_back');
////            $table->dropColumn('first_date_marketing_authorisation');
////            $table->dropColumn('first_date_marketing_authorisation_gr');
////            $table->dropColumn('import_to_system');
////            $table->dropColumn('export_from_system');
////            $table->dropColumn('forecast_export_from_system');
////            $table->dropColumn('ownership');
////            $table->dropColumn('use');
//            $table->dropColumn('class');
        });
    }
}
