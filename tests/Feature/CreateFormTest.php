<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_create_new_forms()
    {
        $this->withExceptionHandling();

        $this->post('/forms', [])
            ->assertStatus(302);
    }

    /** @test */
    public function a_user_can_create_a_new_form()
    {
        $attributes = [
            'title' => 'Form Title',
            'description' => 'My new form'
        ];

        $this->login()
            ->post('/forms', $attributes)
            ->assertStatus(200);

        $this->assertDatabaseHas('forms', $attributes);
    }
}