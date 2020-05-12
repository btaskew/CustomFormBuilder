<?php

namespace Tests\Feature\Form;

use App\Form;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_the_edit_form_page_for_their_form()
    {
        $form = $this->loginUserWithForm();

        $this->get(formPath($form) . '/edit')->assertSee($form->title);
    }

    /** @test */
    public function a_guest_cant_view_the_edit_form_page()
    {
        $this->get('/forms/1/edit')->assertRedirect('login');
    }

    /** @test */
    public function a_user_cant_view_the_edit_form_page_for_another_users_form()
    {
        $form = create(Form::class, ['user_id' => 999]);

        $this->login()->get(formPath($form) . '/edit')->assertStatus(403);
    }

    /** @test */
    public function a_user_can_view_the_edit_form_page_for_another_users_form_they_have_update_access_to()
    {
        $form = $this->createFormWithAccess('update');

        $this->get(formPath($form) . '/edit')->assertSee($form->title);
    }

    /** @test */
    public function a_user_cant_view_the_edit_form_page_for_another_users_form_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');

        $this->get(formPath($form) . '/edit')->assertStatus(403);
    }


    /** @test */
    public function a_user_can_edit_their_form()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id, 'type' => 'email']);

        $attributes = [
            'title' => 'New title',
            'description' => 'New description',
            'open_date' => '1990-01-01',
            'close_date' => '1990-01-02',
            'max_responses' => 3,
            'admin_email' => 'test@email.com',
            'success_text' => 'Form submitted',
            'response_email' => 'Response text',
            'response_email_field' => $question->id,
            'active' => false,
            'folder_id' => 2
        ];

        $this->patch(formPath($form), $attributes)
            ->assertStatus(200);

        $form = $form->fresh();
        $this->assertEquals('New title', $form->title);
        $this->assertEquals('New description', $form->description);
        $this->assertEquals('1990-01-01', $form->open_date);
        $this->assertEquals('1990-01-02', $form->close_date);
        $this->assertEquals(3, $form->max_responses);
        $this->assertEquals('test@email.com', $form->admin_email);
        $this->assertEquals('Form submitted', $form->success_text);
        $this->assertEquals('Response text', $form->response_email);
        $this->assertEquals($question->id, $form->response_email_field);
        $this->assertFalse($form->active);
        $this->assertEquals(2, $form->folder_id);
    }

    /** @test */
    public function a_guest_cant_edit_a_form()
    {
        $form = create(Form::class, ['title' => 'Old title']);

        $this->patch(formPath($form), ['title' => 'New title'])->assertRedirect('login');

        $this->assertEquals('Old title', $form->fresh()->title);
    }

    /** @test */
    public function a_user_cant_edit_another_users_form()
    {
        $form = create(Form::class, ['user_id' => 999, 'title' => 'Old title']);

        $attributes = [
            'title' => 'New title',
            'folder_id' => 1,
            'active' => false
        ];

        $this->login()->patch(formPath($form), $attributes)->assertStatus(403);

        $this->assertEquals('Old title', $form->fresh()->title);
    }

    /** @test */
    public function a_user_can_edit_another_users_form_that_they_have_update_access_to()
    {
        $form = $this->createFormWithAccess('update');

        $attributes = [
            'title' => 'New title',
            'folder_id' => 1,
            'active' => false
        ];

        $this->patch(formPath($form), $attributes)->assertStatus(200);

        $this->assertEquals('New title', $form->fresh()->title);
    }

    /** @test */
    public function a_user_cant_edit_another_users_form_that_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');
        $oldTitle = $form->title;

        $attributes = [
            'title' => 'New title',
            'folder_id' => 1,
            'active' => false
        ];

        $this->patch(formPath($form), $attributes)->assertStatus(403);

        $this->assertEquals($oldTitle, $form->fresh()->title);
    }
}
