<?php

use App\Option;
use App\OptionProfile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransportOptionsToOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            $option = new Option();
            $option->slug = 'delivery';
            $option->option_type = 'transport';
            $option->save();

            $optionProfile = new OptionProfile();
            $optionProfile->option_id = $option->id;
            $optionProfile->title = 'Κόστος Παράδοσης';
            $optionProfile->language_id = 'el';
            $optionProfile->save();

            $optionProfile = new OptionProfile();
            $optionProfile->option_id = $option->id;
            $optionProfile->title = 'Delivery Charge';
            $optionProfile->language_id = 'en';
            $optionProfile->save();

            $option = new Option();
            $option->slug = 'collection';
            $option->option_type = 'transport';
            $option->save();

            $optionProfile = new OptionProfile();
            $optionProfile->option_id = $option->id;
            $optionProfile->title = 'Κόστος Παραλαβής';
            $optionProfile->language_id = 'el';
            $optionProfile->save();

            $optionProfile = new OptionProfile();
            $optionProfile->option_id = $option->id;
            $optionProfile->title = 'Collection Charge';
            $optionProfile->language_id = 'en';
            $optionProfile->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('options', function (Blueprint $table) {
            $options = Option::whereIn('slug', ['delivery', 'collection'])->get();
            foreach ($options as $option) {
                $optionProfiles = $option->profiles;
                foreach ($optionProfiles as $optionProfile) {
                    $optionProfile->delete();
                }
                $option->forceDelete();
            }
        });
    }
}
