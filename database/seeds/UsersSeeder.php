<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create([
            'id' => 1,
            'name' => 'Ben Askew',
            'username' => 'ba329',
        ]);

        $user->assignRole('admin');

        factory(User::class)->create([
            'id' => 2,
            'name' => 'Jane Doe',
            'username' => 'jd456',
            'email' => 'jane@email.com'
        ]);
    }
}
