<?php

use App\OptionLink;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionLinksTable extends Migration
{
    public function moveData() {
        $type_options = DB::table('type_options')->get();
        foreach ($type_options as $type_option) {
            $option_link = new OptionLink;
            $option_link->option_id = $type_option->option_id;
            $option_link->ordering = $type_option->ordering;
            $option_link->option_link_id = $type_option->type_id;
            $option_link->option_link_type = 'App\\Type';
            $option_link->save();
        }

        Schema::drop('type_options');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('option_id')->unsigned();
            $table->mediumInteger('ordering')->default(1000)->index();
            $table->morphs('option_link');
        });

        Schema::table('option_links', function (Blueprint $table) {
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade')->onUpdate('cascade');
        });

        if (Schema::hasTable('type_options')) {
            $this->moveData();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('option_links');
    }
}
