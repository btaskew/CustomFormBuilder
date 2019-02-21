<?php

namespace Tests\Feature\Form;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_the_create_form_page()
    {
        $this->login()->get('/forms/create')->assertSee('Create a new form');
    }

    /** @test */
    public function a_guest_cant_view_the_create_form_page()
    {
        $this->get('/forms/create')->assertRedirect('login');
    }

    /** @test */
    public function a_guest_cant_create_new_forms()
    {
        $this->post('/forms', [])->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_new_form()
    {
        $attributes = [
            'title' => 'Form Title',
            'description' => 'Form description',
            'open_date' => '1990-01-01',
            'close_date' => '1990-01-02',
            'active' => true,
            'admin_email' => 'test@email.com;foo@email.com',
            'success_text' => 'Form submitted',
            'response_email' => 'Response text',
            'folder_id' => 1
        ];

        $this->login()
            ->post('/forms', $attributes)
            ->assertStatus(201);

        $this->assertDatabaseHas('forms', $attributes);
    }
}