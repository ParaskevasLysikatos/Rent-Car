<?php

use App\Category;
use App\Characteristic;
use App\Option;
use App\Type;
use Illuminate\Database\Seeder;

class CategoriesAndTypesSeeder extends Seeder
{
    public function run()
    {
        factory(Option::class, 5)->state('with_profiles')->create();
        factory(Characteristic::class, 5)->state('with_profiles')->create();

        for ($c = 1; $c < 3; $c++) {
            $category = factory(Category::class)->state('with_profiles')->create();

            for ($t = 1; $t < 3; $t++) {
                factory(Type::class)->states(['with_profiles', 'with_options', 'with_characteristics'])->create([
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
