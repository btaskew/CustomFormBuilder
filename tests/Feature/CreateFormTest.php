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
    public function can_view_edit_form_page()
    {
        $form = $this->loginUserWithForm();

        $this->get('/forms/' . $form->id . '/edit')->assertSee($form->title);
    }

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
            'title' => 'Form Title'
        ];

        $this->login()
            ->post('/forms', $attributes)
            ->assertStatus(302);

        $this->assertDatabaseHas('forms', $attributes);
    }

    /** @test */
    public function a_user_can_edit_their_form()
    {
        $form = $this->loginUserWithForm();

        $this->patch('/forms/' . $form->id, ['title' => 'New title'])
            ->assertStatus(200);

        $this->assertEquals('New title', $form->fresh()->title);
    }

    /** @test */
    public function a_user_can_clear_the_description_field()
    {
        $form = $this->loginUserWithForm();

        $this->patch('/forms/' . $form->id, ['title' => 'New title', 'description' => ''])
            ->assertStatus(200);

        $this->assertEquals('', $form->fresh()->description);
    }

    /** @test */
    public function a_user_cant_edit_another_users_form()
    {
        $this->withExceptionHandling();
        $form = create(Form::class, ['user_id' => 999]);

        $this->login()
            ->patch('/forms/' . $form->id, ['title' => 'New title'])
            ->assertStatus(403);
    }
}