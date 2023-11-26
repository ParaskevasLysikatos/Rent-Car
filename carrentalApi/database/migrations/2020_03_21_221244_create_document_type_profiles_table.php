<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentTypeProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_type_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('language_id', 20);
            $table->bigInteger('type_id')->unsigned();

            $table->string('title');
            $table->text('description')->nullable();
        });

        Schema::table('document_type_profiles', function (Blueprint $table) {
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('type_id')->references('id')->on('document_types')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_type_profiles');
    }
}
