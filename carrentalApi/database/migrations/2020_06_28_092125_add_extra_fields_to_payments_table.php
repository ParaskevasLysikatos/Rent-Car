<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('bank_transfer_account')->nullable();
            $table->string('cheque_due_date')->nullable();
            $table->string('cheque_number')->nullable();
            $table->string('credit_card_year')->nullable();
            $table->string('credit_card_month')->nullable();
            $table->string('credit_card_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
           $table->dropColumn('bank_transfer_account');
           $table->dropColumn('cheque_due_date');
           $table->dropColumn('cheque_number');
           $table->dropColumn('credit_card_year');
           $table->dropColumn('credit_card_month');
           $table->dropColumn('credit_card_number');
        });
    }
}
