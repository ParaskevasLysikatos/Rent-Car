<?php

declare(strict_types=1);

use App\Agent;
use App\Booking;
use App\BookingSource;
use App\Brand;
use App\Driver;
use App\Station;
use App\User;
use App\Vehicle;
use Illuminate\Database\Seeder;

class BookingsSeeder extends Seeder
{
    public function run()
    {
        $brands   = Brand::all(['id'])->toArray();
        $agents   = Agent::all(['id'])->toArray();
        $users    = User::all(['id'])->toArray();
        $stations = Station::all(['id'])->toArray();
        $vehicles = Vehicle::all(['id', 'type_id'])->toArray();
        $drivers  = Driver::all(['id'])->toArray();

        $sources = BookingSource::all(['id'])->toArray();

        if (empty($sources)) {
            $sources = factory(BookingSource::class, 2)->state('with_profiles')->create()->toArray();
        }

        $now = new DateTime();

        for ($i = -20; $i <= 20; $i++) {
            $checkout_date = (clone $now)->modify($i . ' weeks');
            $checkin_date  = (clone $checkout_date)->modify(rand(2, 20) . ' days');

            $brand            = $brands[array_rand($brands)];
            $agent            = $agents[array_rand($agents)];
            $user             = $users[array_rand($users)];
            $checkout_station = $stations[array_rand($stations)];
            $checkin_station  = $stations[array_rand($stations)];
            $vehicle          = $vehicles[array_rand($vehicles)];
            $driver           = $drivers[array_rand($drivers)];
            $source           = $sources[array_rand($sources)];

            factory(Booking::class)
                ->create(
                    [
                        'user_id'             => $user['id'],
                        'customer_id'         => $driver['id'],
                        'vehicle_id'          => $vehicle['id'],
                        'type_id'             => $vehicle['type_id'],
                        'brand_id'            => $brand['id'],
                        'agent_id'            => rand(0, 1) == 1 ? $agent['id'] : null,
                        'source_id'           => $source['id'],
                        'checkout_station_id' => $checkout_station['id'],
                        'checkout_datetime'   => $checkout_date,
                        'checkin_station_id'  => $checkin_station['id'],
                        'checkin_datetime'    => $checkin_date,
                    ]
                );
        }
    }
}
