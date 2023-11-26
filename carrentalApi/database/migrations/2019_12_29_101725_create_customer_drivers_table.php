<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->softDeletes();

            $table->string('firstname');
            $table->string('lastname');

            $table->string('email');
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->date('birthday');
            $table->string('fly_number')->nullable();
            $table->string('notes')->nullable();

            $table->string('licence_number')->nullable();
            $table->string('licence_country')->nullable();
            $table->string('licence_created')->nullable();
            $table->string('licence_expire')->nullable();

            $table->string('identification_number')->nullable();
            $table->string('identification_country')->nullable();
            $table->string('identification_created')->nullable();
            $table->string('identification_expire')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers');
    }
}
