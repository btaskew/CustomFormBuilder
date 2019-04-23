<?php

use Illuminate\Database\Migrations\Migration;
use JWWebDev\Admin\Models\Role;

class SeedRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Role::create([
            'role' => 'admin'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Role::where('role', 'admin')->delete();
    }
}
