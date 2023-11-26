<?php

use App\ServiceDetails;
use Illuminate\Database\Seeder;

class VisitSeeder extends Seeder
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

                'slug'     => 'ladia',
                'title'    => 'Λάδια',
                'category' => 'general',
                'order'    => '0',
            ],
            [
                'slug'     => 'filtra-aeros',
                'title'    => 'Φίλτρα αέρος',
                'category' => 'general',
                'order'    => '2',
            ],
            [
                'slug'     => 'filtra-kampinas',
                'title'    => 'Φίλτρα καμπίνας',
                'category' => 'general',
                'order'    => '3',
            ],
            [
                'slug'     => 'filtra-ladiou',
                'title'    => 'Φίλτρα λαδιού',
                'category' => 'general',
                'order'    => '1',
            ],

            [
                'slug'     => 'takaki-empros',
                'title'    => 'Τακάκι εμπρός',
                'category' => 'general',
                'order'    => '4',
            ],

            [
                'slug'     => 'takaki-pisw',
                'title'    => 'Τακάκι πίσω',
                'category' => 'general',
                'order'    => '5',
            ],
            [
                'slug'     => 'psygeio-antipsyktiko',
                'title'    => 'Ψυγείο Αντιψυκτικό',
                'category' => 'general',
                'order'    => '6',
            ],
            [
                'slug'     => 'mpataria',
                'title'    => 'Μπαταρία',
                'category' => 'general',
                'order'    => '7',
            ],

            [
                'slug'     => 'balbolines',
                'title'    => 'Βαλβολίνες',
                'category' => 'general',
                'order'    => '8',
            ],
            [
                'slug'     => 'mpouzi',
                'title'    => 'Μπουζί',
                'category' => 'general',
                'order'    => '9',
            ],
            [
                'slug'     => 'imantas-xronisou',
                'title'    => 'Ιμάντας χρονισμού',
                'category' => 'general',
                'order'    => '10',
            ],
            [
                'slug'     => 'imantas-dyac',
                'title'    => 'Ιμάντας (Δ-Υ-AC)',
                'category' => 'general',
                'order'    => '11',
            ],

            [
                'slug'     => 'rouleman',
                'title'    => 'Ρουλεμάν',
                'category' => 'general',
                'order'    => '12',
            ],
            [
                'slug'     => 'diskoi',
                'title'    => 'Δίσκοι (εμπρός-πίσω)',
                'category' => 'general',
                'order'    => '13',
            ],
            [
                'slug'     => 'symplektes',
                'title'    => 'Συμπλέκτες',
                'category' => 'general',
                'order'    => '14',
            ],

            [
                'slug'     => 'elastika',
                'title'    => 'Ελαστικά',
                'category' => 'general',
                'order'    => '15',
            ],
            [
                'slug'     => 'fwta-epo',
                'title'    => 'Φώτα (Ε-Π-Ο)',
                'category' => 'rent',
                'order'    => '16',
            ],
            [
                'slug'     => 'flas-alarm',
                'title'    => 'Φλάς / Αλάρμ',
                'category' => 'rent',
                'order'    => '17',
            ],
            [
                'slug'     => 'anaptiras',
                'title'    => 'Αναπτήρας',
                'category' => 'rent',
                'order'    => '18',
            ],
            [
                'slug'     => 'yalokatharistires',
                'title'    => 'Υαλοκαθαριστηρες',
                'category' => 'rent',
                'order'    => '19',
            ],
            [
                'slug'     => 'tabla',
                'title'    => 'Τάβλα',
                'category' => 'rent',
                'order'    => '20',
            ],
            [
                'slug'     => 'ac',
                'title'    => 'A/C',
                'category' => 'rent',
                'order'    => '21',
            ],
            [
                'slug'     => 'plafoniera',
                'title'    => 'Πλαφονιέρα',
                'category' => 'rent',
                'order'    => '22',
            ],
            [
                'slug'     => 'radiofono',
                'title'    => 'Ραδιόφωνο',
                'category' => 'rent',
                'order'    => '23',
            ],
            [
                'slug'     => 'korna',
                'title'    => 'Κόρνα',
                'category' => 'rent',
                'order'    => '24',
            ],
            [
                'slug'     => 'parathyra',
                'title'    => 'Παράθυρα',
                'category' => 'rent',
                'order'    => '25',
            ],

            [
                'slug'     => 'saloni',
                'title'    => 'Σαλόνι',
                'category' => 'rent',
                'order'    => '26',
            ],
            [
                'slug'     => 'aera-lastixa',
                'title'    => 'Αέρα / Λάστιχα',
                'category' => 'rent',
                'order'    => '27',
            ],
            [
                'slug'     => 'keraia',
                'title'    => 'Κεραία',
                'category' => 'rent',
                'order'    => '28',
            ],
            [
                'slug'     => 'rezerba',
                'title'    => 'Ρεζέρβα',
                'category' => 'rent',
                'order'    => '29',
            ],
            [
                'slug'     => 'zwni',
                'title'    => 'Ζώνη',
                'category' => 'rent',
                'order'    => '30',
            ],
            [
                'slug'     => 'tasia',
                'title'    => 'Τάσια',
                'category' => 'rent',
                'order'    => '31',
            ],
            [
                'slug'  => 'elegxos',
                'title' => 'Έλεγχος',
            ],
            [
                'slug'  => 'allagi',
                'title' => 'Αλλαγή',
            ],
            [
                'slug'  => 'simplirosi',
                'title' => 'Συμπλήρωση',
            ],
        ];

        foreach ($seeds as $item) {
            if (ServiceDetails::where('slug', $item['slug'])->first()) {
                continue;
            }

            factory(ServiceDetails::class)->create($item);
        }
    }
}
