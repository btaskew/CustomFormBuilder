<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(SampleDataSeeder::class);
    }
}
