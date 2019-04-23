<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'name' => 'Ben Askew',
            'username' => 'ba329',
        ]);

        DB::table('role_user')->insert([
            'role_id' => 1,
            'user_id' => 1
        ]);

        factory(User::class)->create([
            'id' => 2,
            'name' => 'Jane Doe',
            'username' => 'jd456',
            'email' => 'jane@email.com'
        ]);
    }
}
