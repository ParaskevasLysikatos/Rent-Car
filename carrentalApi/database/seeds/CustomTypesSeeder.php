<?php

use Illuminate\Database\Seeder;
use App\FuelTypes;
use App\ColorTypes;
use App\TransmissionTypes;
use App\DriveTypes;
use App\ClassTypes;
use App\OwnershipTypes;
use App\UseTypes;
class CustomTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Set all Fuel Types
        factory(FuelTypes::class)->create([
            'title'      => 'Αέριο(LPG) - Βενζίνη',
            'international_title'     => 'Gas/LPG'
        ]);
        factory(FuelTypes::class)->create([
            'title'      => 'Βενζίνη',
            'international_title'     => 'Gasoline'
        ]);
        factory(FuelTypes::class)->create([
            'title'      => 'Ηλεκτρικό',
            'international_title'     => 'Electric'
        ]);
        factory(FuelTypes::class)->create([
            'title'      => 'Πετρέλαιο',
            'international_title'     => 'Petroleum'
        ]);
        factory(FuelTypes::class)->create([
            'title'      => 'Υβριδικό Βενζίνη',
            'international_title'     => 'Hybrid Gasoline'
        ]);
        factory(FuelTypes::class)->create([
            'title'      => 'Υβριδικό Πετρέλαιο',
            'international_title'     => 'Hybrid Petroleum'
        ]);
        factory(FuelTypes::class)->create([
            'title'      => 'Φυσικό αέριο(CNG)',
            'international_title'     => 'Natural Gas'
        ]);
        factory(FuelTypes::class)->create([
            'title'      => 'Αλλο',
            'international_title'     => 'Other'
        ]);


        //Set Color Titles
        factory(ColorTypes::class)->create([
            'title'      => 'Μαύρο',
            'international_title'     => 'Black'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Λευκό',
            'international_title'     => 'White'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Κόκκινο',
            'international_title'     => 'Red'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Πράσινο',
            'international_title'     => 'Green'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Μπλε',
            'international_title'     => 'Blue'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Κίτρινο',
            'international_title'     => 'Yellow'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Γαλάζιο',
            'international_title'     => 'Light blue'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Μοβ',
            'international_title'     => 'Purple'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Πορτοκαλί',
            'international_title'     => 'Orange'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Ασημί',
            'international_title'     => 'Silver'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Γκρι',
            'international_title'     => 'Gray'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Καφέ',
            'international_title'     => 'Brown'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Κόκκινο σκούρο',
            'international_title'     => 'Dark red'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Μπεζ',
            'international_title'     => 'Beige'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Μπλε σκούρο',
            'international_title'     => 'Dark blue'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Μπορντό',
            'international_title'     => 'Bordeaux'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Μόβ-Βιολετί',
            'international_title'     => 'Purple-Violet'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Πράσινο σκούρο',
            'international_title'     => 'Dark green'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Ροζ',
            'international_title'     => 'Pink'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Χρυσαφί',
            'international_title'     => 'Gold'
        ]);
        factory(ColorTypes::class)->create([
            'title'      => 'Αλλο',
            'international_title'     => 'Other'
        ]);


        //Set Drive Types
        factory(DriveTypes::class)->create([
            'title'      => '4x4'
        ]);
        factory(DriveTypes::class)->create([
            'title'      => 'FWD',
            'short_description'     => 'Προσθιοκίνητο'
        ]);
        factory(DriveTypes::class)->create([
            'title'      => 'RWD',
            'short_description'     => 'Πισωκίνητο'
        ]);

        //Set Transmission Types
        factory(TransmissionTypes::class)->create([
            'title'      => 'Αυτόματο',
            'international_title'     => 'Automatic'
        ]);
        factory(TransmissionTypes::class)->create([
            'title'      => 'Χειροκίνητο',
            'international_title'     => 'Manual'
        ]);

        //Set Class Drive Types
        factory(ClassTypes::class)->create([
            'title'      => 'Mini'
        ]);
        factory(ClassTypes::class)->create([
            'title'      => 'Economy'
        ]);
        factory(ClassTypes::class)->create([
            'title'      => 'Compact'
        ]);
        factory(ClassTypes::class)->create([
            'title'      => 'Full size'
        ]);
        factory(ClassTypes::class)->create([
            'title'      => 'Luxury'
        ]);

        //Set Use Types
        factory(UseTypes::class)->create([
            'title'      => 'Car Rental'
        ]);
        factory(UseTypes::class)->create([
            'title'      => 'Chauffeur'
        ]);
        factory(UseTypes::class)->create([
            'title'      => 'Leasing'
        ]);
        factory(UseTypes::class)->create([
            'title'      => 'Used Car Sales'
        ]);

        //Set OwnerShip Types
        factory(OwnershipTypes::class)->create([
            'title'      => 'Ιδιόκτητο',
            'international_title' => 'Owned'
        ]);
        factory(OwnershipTypes::class)->create([
            'title'      => 'Μίσθωση',
            'international_title' => 'Leasing'
        ]);
        factory(OwnershipTypes::class)->create([
            'title'      => 'Ενοικιαζόμενα μικρής διάρκειας',
            'international_title' => 'Short time rental'
        ]);
        factory(OwnershipTypes::class)->create([
            'title'      => 'Ενοικιαζόμενα μακράς διαρκείας',
            'international_title' => 'Long time rental'
        ]);
    }
}
