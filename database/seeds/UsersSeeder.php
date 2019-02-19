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
        factory(User::class)->create([
            'id' => 1,
            'name' => 'John Doe',
            'username' => 'jd123',
            'email' => 'john@email.com',
            'password' => bcrypt('password')
        ]);
    }
}
