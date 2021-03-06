<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create([
            'id' => 1,
            'name' => 'John Doe',
            'username' => 'jd123',
            'email' => 'john@email.com',
            'password' => bcrypt('password')
        ]);

        $user->assignRole('admin');

        factory(User::class)->create([
            'id' => 2,
            'name' => 'Jane Doe',
            'username' => 'jd456',
            'email' => 'jane@email.com',
            'password' => bcrypt('password')
        ]);
    }
}
