<?php

use App\PeriodicFeeType;
use Illuminate\Database\Seeder;

class PeriodicFeeTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds = [
            [
                'title' => 'Τέλη Κυκλοφορίας',
            ],
            [
                'title' => 'ΚΤΕΟ',
            ],
            [
                'title' => 'Ασφάλεια',
            ],
            [
                'title' => 'Κάρτα Καυσαερίων',
            ],
            [
                'title' => 'Οδική Βοήθεια',
            ],
        ];

        foreach ($seeds as $item) {
            if (PeriodicFeeType::where('title', $item['title'])->first()) {
                continue;
            }

            factory(PeriodicFeeType::class)->create($item);
        }
    }
}
