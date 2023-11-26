<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
                        UsersSeeder::class,

                        BrandsSeeder::class,
                        SeasonsSeeder::class,
                        LocationsSeeder::class,
                        AgentsSeeder::class,

                        ImagesSeeder::class,
                        DocumentsSeeder::class,

                        CompaniesAndDriversSeeder::class,
                        CategoriesAndTypesSeeder::class,

                        Vehicles::class,
                        VisitSeeder::class,
                        PeriodicFeeTypes::class,
                        TransitionTypeSeeder::class,
                        CustomTypesSeeder::class,

                        BookingsSeeder::class,
                        RentalSeeder::class,
                        TransitionsSeeder::class
                    ]
        );
    }

}
