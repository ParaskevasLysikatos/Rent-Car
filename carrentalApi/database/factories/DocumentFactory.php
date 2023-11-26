<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Document;
use App\DocumentType;
use App\DocumentTypeProfile;
use Faker\Generator as Faker;
use http\Client\Curl\User;

$factory->define(Document::class, function (Faker $faker) {
    $ext  = rand(0, 1) == 0 ? 'doc' : 'pdf';
    $path = 'documents/document-' . $faker->randomNumber(4) . $faker->randomNumber(4) . '.' . $ext;

    return [
        'path'      => $path,
        'mime_type' => $ext == 'doc' ? 'application/msword' : 'application/pdf',
    ];
});

$factory->afterMakingState(Document::class, 'with_type', function ($document, $faker) {
    $type              = factory(DocumentType::class)->state('with_profiles')->create();
    $document->type_id = $type->id;
});

$factory->afterMakingState(Document::class, 'with_user', function ($document, $faker) {
    $user              = factory(User::class)->state('with_role')->create();
    $document->user_id = $user->id;
});


$factory->define(DocumentType::class, function (Faker $faker) {
    return [
    ];
});

$factory->afterCreatingState(DocumentType::class, 'with_profiles', function ($type, $faker) {
    factory(DocumentTypeProfile::class)->create([
        'type_id'     => $type->id,
        'language_id' => 'el',
        'title'       => 'Document VehicleType ' . $type->id,
    ]);

    factory(DocumentTypeProfile::class)->create([
        'type_id'     => $type->id,
        'language_id' => 'en',
        'title'       => 'Τύπος Εγγράφου ' . $type->id,
    ]);
});

$factory->define(DocumentTypeProfile::class, function (Faker $faker) {
    $language_id = Arr::random(['el', 'en']);

    return [
        'language_id' => $language_id,
        'title'       => $faker->text(50),
        'description' => $faker->text(200),
    ];
});
