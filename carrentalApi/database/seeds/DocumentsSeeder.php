<?php

use App\Document;
use App\DocumentType;
use App\User;
use Illuminate\Database\Seeder;

class DocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::get(['id'])->toArray();
        $types = factory(DocumentType::class, 3)->states('with_profiles')->create();

        foreach ($types as $type) {
            $user = $users[array_rand($users)];

            factory(Document::class, 5)->create(
                [
                    'type_id' => $type->id,
                    'user_id' => $user['id'],
                ]
            );
        }
    }
}
