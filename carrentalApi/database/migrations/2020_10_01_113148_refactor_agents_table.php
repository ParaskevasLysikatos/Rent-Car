<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agents', function(Blueprint $table) {
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->bigInteger('parent_agent_id')->unsigned()->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parent_agent_id')->references('id')->on('agents')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agents', function(Blueprint $table) {
            $table->dropForeign('agents_company_id_foreign');
            $table->dropForeign('agents_parent_agent_id_foreign');
            $table->dropColumn('company_id');
            $table->dropColumn('parent_agent_id');
        });
    }
}
