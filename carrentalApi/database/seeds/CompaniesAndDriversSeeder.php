<?php

use App\Company;
use App\Contact;
use App\Driver;
use Illuminate\Database\Seeder;

class CompaniesAndDriversSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Company::class, 5)->create();

        for ($c = 1; $c < 10; $c++) {
            $contact = factory(Contact::class)->create();

            factory(Driver::class)->create([
                'contact_id' => $contact->id,
                'role' => 'employee'
            ]);
        }
    }
}
