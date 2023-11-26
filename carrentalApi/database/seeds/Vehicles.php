<?php

use App\Type;
use App\Vehicle;
use Illuminate\Database\Seeder;

class Vehicles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = Type::all();

        foreach ($types as $type) {
            factory(Vehicle::class, 5)->states('with_profiles', 'with_license_plates')->create(['type_id' => $type->id]);
        }
    }
}
