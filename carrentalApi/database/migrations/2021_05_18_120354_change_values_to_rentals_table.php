<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeValuesToRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('rentals')->where('status', 'checked-in')->update(['status' => 'check-in']);
        DB::table('rentals')->where('status', 'pre-checked-in')->update(['status' => 'pre-check']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('rentals')->where('status', 'check-in')->update(['status' => 'check-in']);
        DB::table('rentals')->where('status', 'pre-check')->update(['status' => 'pre-checked-in']);
    }
}
