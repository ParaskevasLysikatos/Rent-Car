<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Agent;
use App\ContactInformation;
use App\Document;
use App\DocumentType;
use App\User;
use Faker\Generator as Faker;

$factory->define(Agent::class, function (Faker $faker) {
    return [
        'name'       => 'Agent ' . $faker->randomNumber(4) . $faker->randomNumber(4),
        'commission' => $faker->randomFloat(2, 0, 5),
    ];
});

$factory->afterMakingState(Agent::class, 'with_contact_information', function ($agent, $faker) {
    /** @var ContactInformation $contact_information */
    $contact_information = factory(ContactInformation::class)->create();

    $agent->contact_information_id = $contact_information->id;
});


$factory->afterCreatingState(Agent::class, 'with_documents', function ($agent, $faker) {
    $user          = factory(User::class)->state('with_role')->create();
    $document_type = factory(DocumentType::class)->create();
    $documents     = factory(Document::class, 3)->create(['type_id' => $document_type->id, 'user_id' => $user->id]);

    foreach ($documents as $item) {
        $agent->addDocument($item);
    }
});
