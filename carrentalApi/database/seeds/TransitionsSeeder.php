<?php

use App\Driver;
use App\Place;
use App\Station;
use App\Transition;
use App\TransitionType;
use App\User;
use App\Vehicle;
use Illuminate\Database\Seeder;

class TransitionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users    = User::all(['id'])->toArray();
        $stations = Station::all(['id'])->toArray();
        $vehicles = Vehicle::all(['id', 'type_id'])->toArray();
        $drivers  = Driver::all(['id'])->toArray();
        $types    = TransitionType::all(['id'])->toArray();

        $places = Place::all(['id'])->toArray();

        if (empty($places)) {
            $places = factory(Place::class, 2)->state('with_profiles')->create();
        }

        $now = new DateTime();

        for ($i = -20; $i <= 20; $i++) {
            $checkout_date = (clone $now)->modify($i . ' weeks');
            $checkin_date  = (clone $checkout_date)->modify(rand(2, 20) . ' weeks');

            $user             = $users[array_rand($users)];
            $checkout_station = $stations[array_rand($stations)];
            $checkin_station  = $stations[array_rand($stations)];
            $vehicle          = $vehicles[array_rand($vehicles)];
            $driver           = $drivers[array_rand($drivers)];
            $checkout_place   = $places[array_rand($places)];
            $checkin_place    = $places[array_rand($places)];
            $type             = $types[array_rand($types)];

            factory(Transition::class)->create([
                'co_datetime' => $checkout_date,
                'ci_datetime' => $checkin_date,

                'driver_id' => $driver['id'],
                'ci_user_id' => $user['id'],

                'ci_station_id' => $checkin_station['id'],
                'ci_place_id' => $checkin_place['id'],
                'vehicle_id' => $vehicle['id'],
                'co_user_id' => $user['id'],

                'co_station_id' => $checkout_station['id'],
                'co_place_id' => $checkout_place['id'],

                'type_id' => $type['id']
            ]);
        }
    }
}
