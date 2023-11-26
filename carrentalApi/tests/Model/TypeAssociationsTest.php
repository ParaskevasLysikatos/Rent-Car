<?php


namespace Tests\Model;

use App\Characteristic;
use App\Image;
use App\Option;
use App\Type;
use Tests\TestCase;

class TypeAssociationsTest extends TestCase
{
    protected $truncate = [
        'categories',
        'types',
        'images',
        'options',
        'characteristics',
    ];

    public function testTypeOptions()
    {
        /** @var Type $type */
        $type = factory(Type::class)->create();

        $option1 = factory(Option::class)->create();
        $option2 = factory(Option::class)->create();
        $option3 = factory(Option::class)->create();
        $option4 = factory(Option::class)->create();

        $type->addOption($option1, 2);
        $type->addOption($option2, 1);
        $type->addOption($option3, 2);
        $type->addOption($option4);

        $expected = [
            $option2->id, // ordering: 1
            $option1->id, // ordering: 2, but smaller id than option 3
            $option3->id, // ordering: 2
            $option4->id, // ordering: 100
        ];

        $options = $type->options()->get()->toArray();
        $options = array_map(function ($item) {
            return $item['id'];
        }, $options);

        $this->assertEquals($expected, $options);
    }

    public function testTypeCharacteristics()
    {
        /** @var Type $type */
        $type = factory(Type::class)->create();

        $characteristic1 = factory(Characteristic::class)->create();
        $characteristic2 = factory(Characteristic::class)->create();
        $characteristic3 = factory(Characteristic::class)->create();
        $characteristic4 = factory(Characteristic::class)->create();

        $type->addCharacteristic($characteristic1, 2);
        $type->addCharacteristic($characteristic2, 1);
        $type->addCharacteristic($characteristic3, 2);
        $type->addCharacteristic($characteristic4);

        $expected = [
            $characteristic2->id, // ordering: 1
            $characteristic1->id, // ordering: 2, but smaller id than characteristic 3
            $characteristic3->id, // ordering: 2
            $characteristic4->id, // ordering: 100
        ];

        $characteristics = $type->characteristics()->get()->toArray();
        $characteristics = array_map(function ($item) {
            return $item['id'];
        }, $characteristics);

        $this->assertEquals($expected, $characteristics);
    }

    public function testTypeImages()
    {
        /** @var Type $type */
        $type = factory(Type::class)->create();

        $image1 = factory(Image::class)->create(['path' => 'image1.jpg']);
        $image2 = factory(Image::class)->create(['path' => 'image2.jpg']);
        $image3 = factory(Image::class)->create(['path' => 'image3.jpg']);
        $image4 = factory(Image::class)->create(['path' => 'image4.jpg']);

        $type->addImage($image1, FALSE, 2);
        $type->addImage($image2, TRUE, 1);
        $type->addImage($image3, FALSE, 2);
        $type->addImage($image4, FALSE);

        $expected = [
            $image2->id, // ordering: 1
            $image1->id, // ordering: 2, but smaller id than image 3
            $image3->id, // ordering: 2
            $image4->id, // ordering: 100
        ];

        $images = $type->images()->get()->toArray();
        $images = array_map(function ($item) {
            return $item['id'];
        }, $images);

        $this->assertEquals($expected, $images);
    }

}
