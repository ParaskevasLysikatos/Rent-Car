<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'user_roles',
            function (Blueprint $table) {
                $table->string('id', 20)->primary();

                $table->timestamps();

                $table->string('title');
                $table->text('description')->nullable();
            }
        );

        $created_at = date('Y-m-d H:i:s');
        DB::table('user_roles')->insert(
            [
                [
                    'id'         => 'root',
                    'title'      => 'Super User',
                    'created_at' => $created_at,
                ],
                [
                    'id'         => 'admin',
                    'title'      => 'Administrator',
                    'created_at' => $created_at,
                ],
                [
                    'id'         => 'service',
                    'title'      => 'Service',
                    'created_at' => $created_at,
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
        Schema::dropIfExists('user_roles');
    }
}
