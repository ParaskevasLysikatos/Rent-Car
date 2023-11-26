<?php

use App\Location;
use App\LocationProfile;
use App\Place;
use App\PlaceProfile;
use App\Station;
use App\StationProfile;
use Illuminate\Database\Seeder;

class LocationsSeeder extends Seeder
{
    public function run()
    {
        $seeds = [
            [
                'slug'      => 'skg',
                'latitude'  => '40.736851',
                'longitude' => '22.920227',
                'title_el'  => 'Θεσσαλονίκη',
                'title_en'  => 'Thessaloniki',
            ],
            [
                'slug'      => 'athens',
                'latitude'  => '37.98381',
                'longitude' => '23.727539',
                'title_el'  => 'Αθήνα',
                'title_en'  => 'Athens',
            ],
        ];

        foreach ($seeds as $item) {
            if (Location::where('slug', $item['slug'])->first()) {
                continue;
            }

            $location = factory(Location::class)->create(
                [
                    'slug'      => $item['slug'],
                    'latitude'  => $item['latitude'],
                    'longitude' => $item['longitude'],
                ]
            );

            factory(LocationProfile::class)->create(
                [
                    'location_id' => $location->id,
                    'language_id' => 'el',
                    'title'       => $item['title_el'],
                ]
            );

            factory(LocationProfile::class)->create(
                [
                    'location_id' => $location->id,
                    'language_id' => 'el',
                    'title'       => $item['title_en'],
                ]
            );

            for ($i = 1; $i < 5; $i++) {
                $station = factory(Station::class)->create(
                    [
                    'location_id' => $location->id,
                    'slug'        => $location->slug . '-' . $i,
                    'latitude'    => $location->latitude,
                    'longitude'   => $location->longitude,
                ]);

                factory(StationProfile::class)->create([
                    'station_id'  => $station->id,
                    'language_id' => 'el',
                    'title'       => $item['title_el'] . ' ' . $station->id,
                ]);

                factory(StationProfile::class)->create([
                    'station_id'  => $station->id,
                    'language_id' => 'en',
                    'title'       => $item['title_en'] . ' ' . $station->id,
                ]);
            }

            for ($i = 1; $i < 5; $i++) {
                $place = factory(Place::class)->create([
                    'slug'        => $location->slug . '-' . $i,
                    'latitude'    => $location->latitude,
                    'longitude'   => $location->longitude,
                ]);

                factory(PlaceProfile::class)->create([
                    'place_id'  => $place->id,
                    'language_id' => 'el',
                    'title'       => $item['title_el'] . ' ' . $place->id,
                ]);

                factory(PlaceProfile::class)->create([
                    'place_id'  => $place->id,
                    'language_id' => 'en',
                    'title'       => $item['title_en'] . ' ' . $place->id,
                ]);
            }
        }
    }
}
