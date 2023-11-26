<?php

use App\CancelReason;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCancelReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancel_reasons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('title');
        });

        $titles = [
            'Canceled by customer',
            'Non-show',
            'Duplicate',
            'Sold'
        ];
        foreach ($titles as $i => $title) {
            $cancel_reason = new CancelReason();
            $cancel_reason->id = $i+1;
            $cancel_reason->title = $title;
            $cancel_reason->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cancel_reasons');
    }
}
