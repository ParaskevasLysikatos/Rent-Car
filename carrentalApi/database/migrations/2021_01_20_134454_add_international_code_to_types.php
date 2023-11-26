<?php

use App\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInternationalCodeToTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('types', function (Blueprint $table) {
            $table->string('international_code')->nullable();
        });

        $types = Type::get();
        foreach ($types as $type) {
            $type->international_code = DB::table('categories')->where('id', $type->category_id)->first()->international_code;
            $type->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('types', function (Blueprint $table) {
            $table->dropColumn('international_code');
        });
    }
}
