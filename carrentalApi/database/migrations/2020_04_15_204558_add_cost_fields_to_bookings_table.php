<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostFieldsToBookingsTable extends Migration
{

    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->integer('distance')->unsigned()->default(0)->change();
            $table->decimal('distance_rate', 20, 2)->default('0.0')->index()->after('distance');

            $table->decimal('transport_fee', 20, 2)->default('0.0')->index();
            $table->decimal('insurance_fee', 20, 2)->default('0.0')->index();
            $table->decimal('options_fee', 20, 2)->default('0.0')->index();

            $table->decimal('fuel_fee', 20, 2)->default('0.0')->index();
            $table->decimal('subcharges_fee', 20, 2)->default('0.0')->index();

            $table->dropColumn('discount_percentage');

            $table->decimal('discount', 6, 2)->default('0.0')->index();
            $table->decimal('voucher', 20, 2)->default('0.0')->index();

            $table->decimal('total', 20, 2)->default('0.0')->index();
            $table->decimal('total_net', 20, 2)->default('0.0')->index();
            $table->decimal('total_paid', 20, 2)->default('0.0')->index();
            $table->decimal('vat', 6, 2)->default('24.0')->index();

            $table->decimal('balance', 20, 2)->default('0.0')->index();
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('discount_percentage', 6, 2)->default('0.0')->index();

            $table->dropColumn('distance_rate');

            $table->dropColumn('insurance_fee');
            $table->dropColumn('options_fee');
            $table->dropColumn('transport_fee');
            $table->dropColumn('fuel_fee');
            $table->dropColumn('subcharges_fee');

            $table->dropColumn('discount');
            $table->dropColumn('voucher');

            $table->dropColumn('total');
            $table->dropColumn('total_net');
            $table->dropColumn('total_paid');
            $table->dropColumn('vat');

            $table->dropColumn('balance');
        });
    }
}
