<?php

use App\TransitionType;
use Illuminate\Database\Seeder;

class TransitionTypeSeeder extends Seeder
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
                'title' => 'Αλλαγή σταθμού',
            ],
            [
                'title' => 'Αντικατάσταση οχήματος',
            ],
            [
                'title' => 'Μίσθωση',
            ],
            [
                'title' => 'Παραλαβή-Μεταφορά (προσωπικού/πελάτη)',
            ],
            [
                'title' => 'Στήσιμο (σε ξενοδοχείο)',
            ],
            [
                'title' => 'Άλλο',
            ],
        ];

        foreach ($seeds as $item) {
            if (TransitionType::where('title', $item['title'])->first()) {
                continue;
            }

            factory(TransitionType::class)->create($item);
        }

    }
}
