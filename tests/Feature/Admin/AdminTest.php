<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_user_can_view_the_admin_dashboard()
    {
        $this->loginAdmin()->get('/admin')->assertSee('Admin dashboard');
    }

    /** @test */
    public function a_guest_cant_view_the_admin_dashboard()
    {
        $this->get('/admin')->assertRedirect('login');
    }

    /** @test */
    public function a_standard_user_cant_view_the_admin_dashboard()
    {
        $this->login()->get('/admin')->assertStatus(403);
    }
}
