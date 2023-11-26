<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();

            $table->string('slug', 100)->unique();
            $table->decimal('cost', 20, 2)->nullable();
            $table->decimal('cost_daily', 20, 2)->nullable();
            $table->boolean('active_daily_cost')->nullable();
            $table->decimal('cost_max', 20, 2)->nullable();
            $table->integer('items_max')->nullable();
            $table->boolean('default_on')->nullable();
            $table->bigInteger('order')->default(0);
            $table->string('icon')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('options');
    }
}
