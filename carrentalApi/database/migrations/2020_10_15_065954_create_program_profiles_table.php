<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('language_id', 20);
            $table->bigInteger('program_id')->unsigned();

            $table->string('title');
        });

        Schema::table('program_profiles', function (Blueprint $table) {
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::table('program_profiles')->insert([
            [
                'language_id' => 'el',
                'program_id' => 1,
                'title' => 'Προμήθεια'
            ],
            [
                'language_id' => 'el',
                'program_id' => 2,
                'title' => 'Πελάτης'
            ],
            [
                'language_id' => 'el',
                'program_id' => 3,
                'title' => 'Προπληρωμένο από πελάτη'
            ],
            [
                'language_id' => 'el',
                'program_id' => 4,
                'title' => 'Πλήρης Πίστωση'
            ],
            [
                'language_id' => 'el',
                'program_id' => 5,
                'title' => 'Προπληρωμένο'
            ],
            [
                'language_id' => 'el',
                'program_id' => 6,
                'title' => 'Πλήρως προπληρωμένο'
            ],
            [
                'language_id' => 'en',
                'program_id' => 1,
                'title' => 'Commission'
            ],
            [
                'language_id' => 'en',
                'program_id' => 2,
                'title' => 'Customer'
            ],
            [
                'language_id' => 'en',
                'program_id' => 3,
                'title' => 'Customer Prepaid'
            ],
            [
                'language_id' => 'en',
                'program_id' => 4,
                'title' => 'Full Credit'
            ],
            [
                'language_id' => 'en',
                'program_id' => 5,
                'title' => 'Prepaid'
            ],
            [
                'language_id' => 'en',
                'program_id' => 6,
                'title' => 'Full Prepaid'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_profiles');
    }
}
