<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'languages',
            function (Blueprint $table) {
                $table->string('id', 20)->primary();

                $table->bigInteger('order')->default(0);

                $table->string('title');
                $table->string('title_international')->nullable();
            }
        );

        DB::table('languages')->insert(
            [
                [
                    'id'    => 'el',
                    'title' => 'Ελληνικά',
                ],
                [
                    'id'    => 'en',
                    'title' => 'Αγγλικά',
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
