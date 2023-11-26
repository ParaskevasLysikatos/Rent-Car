<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->softDeletes();
            $table->timestamps();

            $table->string('slug',100);
            $table->boolean('rental');
            $table->boolean('commission');
            $table->boolean('extras_booking');
            $table->boolean('extras_rental');
        });

        DB::table('programs')->insert([
            [
                'id' => 1,
                'slug' => 'commission',
                'rental' => 0,
                'commission' => 1,
                'extras_booking' => 0,
                'extras_rental' => 0
            ],
            [
                'id' => 2,
                'slug' => 'customer',
                'rental' => 0,
                'commission' => 0,
                'extras_booking' => 0,
                'extras_rental' => 0
            ],
            [
                'id' => 3,
                'slug' => 'customer_prepaid',
                'rental' => 0,
                'commission' => 0,
                'extras_booking' => 0,
                'extras_rental' => 0
            ],
            [
                'id' => 4,
                'slug' => 'full_credit',
                'rental' => 1,
                'commission' => 1,
                'extras_booking' => 1,
                'extras_rental' => 1
            ],
            [
                'id' => 5,
                'slug' => 'prepaid',
                'rental' => 1,
                'commission' => 1,
                'extras_booking' => 0,
                'extras_rental' => 0
            ],
            [
                'id' => 6,
                'slug' => 'full_prepaid',
                'rental' => 1,
                'commission' => 1,
                'extras_booking' => 1,
                'extras_rental' => 0
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programs');
    }
}
