<?php

use App\Agent;
use Illuminate\Database\Seeder;

class AgentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Agent::class, 5)->states(['with_contact_information'])->create();
    }
}
