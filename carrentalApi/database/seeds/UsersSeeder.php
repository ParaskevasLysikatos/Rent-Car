<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
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
                'role_id' => 'root',
                'email'   => 'yrizos@gmail.com',
            ],
            [
                'role_id' => 'root',
                'email'   => 'gerasimos@e-leoforos.gr',
            ],
            [
                'role_id' => 'root',
                'email'   => 'e.gjoni123@gmail.com',
                'password'   => Hash::make('123456789'),
            ],
        ];

        foreach ($seeds as $item) {
            if (User::where('email', $item['email'])->first()) {
                continue;
            }

            factory(User::class)->create($item);
        }
    }
}
