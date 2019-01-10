<?php

namespace Tests\Feature;

use App\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_the_create_form_page()
    {
        $this->login()->get('/forms/create')->assertSee('Create a new form');
    }

    /** @test */
    public function a_guest_cant_create_new_forms()
    {
        $this->withExceptionHandling();

        $this->post('/forms', [])
            ->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_new_form()
    {
        $attributes = [
            'title' => 'Form Title',
            'open_date' => '1990-01-01',
            'close_date' => '1990-01-02',
        ];

        $this->login()
            ->post('/forms', $attributes)
            ->assertStatus(302);

        $this->assertDatabaseHas('forms', $attributes);
    }
}