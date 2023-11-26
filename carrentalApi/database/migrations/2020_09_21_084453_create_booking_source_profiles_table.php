<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingSourceProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_sources', function (Blueprint $table) {
            $table->softDeletes();
            $table->string('slug', 100)->unique();
            $table->dropColumn('title');
            $table->dropColumn('description');
        });

        Schema::create('booking_source_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('language_id', 20);
            $table->bigInteger('booking_source_id')->unsigned();

            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('booking_source_profiles', function (Blueprint $table) {
            $table->foreign('booking_source_id')->references('id')->on('booking_sources')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_sources', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropColumn('slug');
            $table->string('title');
            $table->string('description');
        });
        Schema::dropIfExists('booking_source_profiles');
    }
}
