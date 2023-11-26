<?php

use App\Agent;
use App\BookingSource;
use App\Brand;
use App\Driver;
use App\Rental;
use App\Station;
use App\User;
use App\Vehicle;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands   = Brand::all();
        $agents   = Agent::all();
        $sources  = BookingSource::all();
        $users    = User::all();
        $stations = Station::all();
        $vehicles = Vehicle::all();
        $drivers  = Driver::all();

        foreach ($vehicles as $vehicle) {
            $driver = $drivers->random();

            $rental = factory(Rental::class)
                ->create([
                    'user_id'             => $users->random()->id,
                    'driver_id'           => $driver->id,
                    'vehicle_id'          => $vehicle->id,
                    'type_id'             => $vehicle->type_id,
                    'brand_id'            => $brands->random()->id,
                    'agent_id'            => rand(0, 1) == 1 ? $agents->random()->id : NULL,
                    'source_id'           => $sources->random()->id,
                    'checkout_station_id' => $stations->random()->id,
                    'checkin_station_id'  => $stations->random()->id,
                ]);
        }
    }
}
