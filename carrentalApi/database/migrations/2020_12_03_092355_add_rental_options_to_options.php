<?php

use App\Option;
use App\OptionProfile;
use Illuminate\Database\Migrations\Migration;

class AddRentalOptionsToOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $option = new Option();
        $option->slug = 'rental';
        $option->option_type = 'rental_charges';
        $option->save();

        $optionProfile = new OptionProfile();
        $optionProfile->option_id = $option->id;
        $optionProfile->title = 'Μίσθωμα Οχήματος';
        $optionProfile->language_id = 'el';
        $optionProfile->save();

        $optionProfile = new OptionProfile();
        $optionProfile->option_id = $option->id;
        $optionProfile->title = 'Vehicle Rental';
        $optionProfile->language_id = 'en';
        $optionProfile->save();

        $option = new Option();
        $option->slug = 'additional-mileage';
        $option->option_type = 'rental_charges';
        $option->save();

        $optionProfile = new OptionProfile();
        $optionProfile->option_id = $option->id;
        $optionProfile->title = 'Επιπλέον Χιλιόμετρα';
        $optionProfile->language_id = 'el';
        $optionProfile->save();

        $optionProfile = new OptionProfile();
        $optionProfile->option_id = $option->id;
        $optionProfile->title = 'Additional Mileage';
        $optionProfile->language_id = 'en';
        $optionProfile->save();

        $option = new Option();
        $option->slug = 'additional-rental';
        $option->option_type = 'rental_charges';
        $option->save();

        $optionProfile = new OptionProfile();
        $optionProfile->option_id = $option->id;
        $optionProfile->title = 'Επιπλέον Χρέωση Μισθώματος';
        $optionProfile->language_id = 'el';
        $optionProfile->save();

        $optionProfile = new OptionProfile();
        $optionProfile->option_id = $option->id;
        $optionProfile->title = 'Additional Vehicle Rental';
        $optionProfile->language_id = 'en';
        $optionProfile->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $options = Option::where('option_type', 'rental_charges')->get();
        foreach ($options as $option) {
            $optionProfiles = $option->profiles;
            foreach ($optionProfiles as $optionProfile) {
                $optionProfile->delete();
            }
            $option->forceDelete();
        }
    }
}
