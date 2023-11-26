<?php

declare(strict_types=1);

namespace Tests\Search;

use App\LicencePlate;
use App\Type;
use App\Vehicle;
use App\VehicleProfile;

class VehicleSearchScenarios extends SearchTestCase
{

    public function test_search_exclude_vehicle_ids(): void
    {
        $vehicle_1 = self::getVehicle(['make' => 'Ford', 'model' => 'Puma', 'vin' => time() . 1]);
        $vehicle_2 = self::getVehicle(['make' => 'Ford', 'model' => 'Puma', 'vin' => time() . 2]);
        $vehicle_3 = self::getVehicle(['make' => 'Ford', 'model' => 'Puma', 'vin' => time() . 3]);

        $result = array_map(
            function ($item) {
                return (int)$item['id'];
            },
            Vehicle::getSearchQuery('Puma')->get()->toArray()
        );

        self::assertContains($vehicle_1->id, $result);
        self::assertContains($vehicle_2->id, $result);
        self::assertContains($vehicle_3->id, $result);

        // same seach, but exclude $vehicle_1
        $result = array_map(
            function ($item) {
                return (int)$item['id'];
            },
            Vehicle::getSearchQuery('Puma', 'el', null, [$vehicle_1->id])->get()->toArray()
        );

        self::assertNotContains($vehicle_1->id, $result);
        self::assertContains($vehicle_2->id, $result);
        self::assertContains($vehicle_3->id, $result);

        // same seach, but exclude $vehicle_2
        $result = array_map(
            function ($item) {
                return (int)$item['id'];
            },
            Vehicle::getSearchQuery('Puma', 'el', null, [$vehicle_2->id])->get()->toArray()
        );

        self::assertContains($vehicle_1->id, $result);
        self::assertNotContains($vehicle_2->id, $result);
        self::assertContains($vehicle_3->id, $result);

        // same seach, but exclude $vehicle_3
        $result = array_map(
            function ($item) {
                return (int)$item['id'];
            },
            Vehicle::getSearchQuery('Puma', 'el', null, [$vehicle_3->id])->get()->toArray()
        );

        self::assertContains($vehicle_1->id, $result);
        self::assertContains($vehicle_2->id, $result);
        self::assertNotContains($vehicle_3->id, $result);

        // same seach, but exclude $vehicle_1, $vehicle_2
        $result = array_map(
            function ($item) {
                return (int)$item['id'];
            },
            Vehicle::getSearchQuery('Puma', 'el', null, [$vehicle_1->id, $vehicle_2->id])->get()->toArray()
        );

        self::assertNotContains($vehicle_1->id, $result);
        self::assertNotContains($vehicle_2->id, $result);
        self::assertContains($vehicle_3->id, $result);

        // same seach, but exclude $vehicle_1, $vehicle_3
        $result = array_map(
            function ($item) {
                return (int)$item['id'];
            },
            Vehicle::getSearchQuery('Puma', 'el', null, [$vehicle_1->id, $vehicle_3->id])->get()->toArray()
        );

        self::assertNotContains($vehicle_1->id, $result);
        self::assertContains($vehicle_2->id, $result);
        self::assertNotContains($vehicle_3->id, $result);

        // same seach, but exclude $vehicle_1, $vehicle_2, $vehicle_3
        $result = array_map(
            function ($item) {
                return (int)$item['id'];
            },
            Vehicle::getSearchQuery('Puma', 'el', null, [$vehicle_1->id, $vehicle_2->id, $vehicle_3->id])->get()->toArray()
        );

        self::assertEmpty($result);

        $vehicle_1->forceDelete();
        $vehicle_2->forceDelete();
        $vehicle_3->forceDelete();
    }

    /**
     * Test that when searching with group_id
     * we only get results for that group
     */
    public function test_search_group(): void
    {
        $type_1 = factory(Type::class)->create();
        $type_2 = factory(Type::class)->create();

        $vehicle_1 = self::getVehicle(
            ['type_id' => $type_1->id, 'make' => 'seat', 'model' => 'ibiza', 'vin' => time() . rand(1, 100)]
        );

        $vehicle_2 = self::getVehicle(
            ['type_id' => $type_2->id, 'make' => 'seat', 'model' => 'ibiza', 'vin' => time() . rand(1, 100)]
        );

        $result = Vehicle::getSearchQuery('Seat', 'el', $type_1->id)->get()->toArray();

        self::assertEquals(1, count($result));
        self::assertEquals($vehicle_1->id, $result[0]['id']);
        self::assertEquals($type_1->id, $result[0]['type_id']);
        self::assertEquals($type_1->id, $result[0]['group']['id']);

        $result = Vehicle::getSearchQuery('Seat', 'el', $type_2->id)->get()->toArray();

        self::assertEquals(1, count($result));
        self::assertEquals($vehicle_2->id, $result[0]['id']);
        self::assertEquals($type_2->id, $result[0]['type_id']);
        self::assertEquals($type_2->id, $result[0]['group']['id']);


        $vehicle_1->forceDelete();
        $vehicle_2->forceDelete();

        $type_1->forceDelete();
        $type_2->forceDelete();
    }

    public function test_search_licence_plates(): void
    {
        $vehicle = self::getVehicle(['make' => 'seat', 'model' => 'ibiza', 'vin' => time()]);

        $plate_1                = new LicencePlate();
        $plate_1->vehicle_id    = $vehicle->id;
        $plate_1->licence_plate = 'WWW 1234';
        $plate_1->save();

        $plate_2                = new LicencePlate();
        $plate_2->vehicle_id    = $vehicle->id;
        $plate_2->licence_plate = 'YYYY 9999';
        $plate_2->save();

        $result = Vehicle::getSearchQuery('www 1234', 'el')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('www', 'el')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('1234', 'el')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('yyyy 9999', 'el')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('yyyy', 'el')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('9999', 'el')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $plate_1->forceDelete();
        $plate_2->forceDelete();
        $vehicle->forceDelete();
    }

    public function test_search_profile_title_el(): void
    {
        $vehicle = self::getVehicle(['make' => 'seat', 'model' => 'ibiza', 'vin' => time()]);

        $profile              = new VehicleProfile();
        $profile->vehicle_id  = $vehicle->id;
        $profile->language_id = 'el';
        $profile->title       = 'Ford Puma';
        $profile->save();

        $profile              = new VehicleProfile();
        $profile->vehicle_id  = $vehicle->id;
        $profile->language_id = 'en';
        $profile->title       = 'Seat Ibiza'; // Incompatible value here to make sure we are hitting the greek profile
        $profile->save();

        $result = Vehicle::getSearchQuery('ford puma', 'el')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('ford', 'el')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('puma', 'el')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('fiat', 'el')->first();
        self::assertEmpty($result);

        $result = Vehicle::getSearchQuery('panda', 'el')->first();
        self::assertEmpty($result);

        $profile->forceDelete();
        $vehicle->forceDelete();
    }

    public function test_search_profile_title_en(): void
    {
        $vehicle = self::getVehicle(['make' => 'seat', 'model' => 'ibiza', 'vin' => time()]);

        $profile              = new VehicleProfile();
        $profile->vehicle_id  = $vehicle->id;
        $profile->language_id = 'el';
        $profile->title       = 'Seat Ibiza'; // Incompatible value here to make sure we are hitting the greek profile
        $profile->save();

        $profile              = new VehicleProfile();
        $profile->vehicle_id  = $vehicle->id;
        $profile->language_id = 'en';
        $profile->title       = 'Ford Puma';
        $profile->save();

        $result = Vehicle::getSearchQuery('ford puma', 'en')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('ford', 'en')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('puma', 'en')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('fiat', 'en')->first();
        self::assertEmpty($result);

        $result = Vehicle::getSearchQuery('panda', 'en')->first();
        self::assertEmpty($result);

        $profile->forceDelete();
        $vehicle->forceDelete();
    }

    public function test_search_make(): void
    {
        $vehicle = self::getVehicle(['make' => 'Ford', 'model' => 'Puma', 'vin' => time()]);

        $result = Vehicle::getSearchQuery('ford')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('FORD')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('   FoRd   ')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('seat')->first();
        self::assertEmpty($result);

        $result = Vehicle::getSearchQuery('BMW')->first();
        self::assertEmpty($result);

        $vehicle->forceDelete();
    }

    public function test_search_model(): void
    {
        $vehicle = self::getVehicle(['make' => 'Ford', 'model' => 'Puma', 'vin' => time()]);

        $result = Vehicle::getSearchQuery('Puma')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('puma')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('PUMA')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('   pUmA   ')->first()->toArray();
        self::assertEquals($vehicle->id, $result['id']);

        $result = Vehicle::getSearchQuery('ibiza')->first();
        self::assertEmpty($result);

        $result = Vehicle::getSearchQuery('fiesta')->first();
        self::assertEmpty($result);

        $vehicle->forceDelete();
    }

}
