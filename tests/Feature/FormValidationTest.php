<?php

namespace Tests\Feature;

use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        $this->withExceptionHandling();
    }

    /** @test */
    public function a_form_title_is_required()
    {
        $this->login()
            ->json('post', '/forms', [])
            ->assertStatus(422)
            ->assertSee('title');
    }

    /** @test */
    public function a_form_title_cant_be_too_long()
    {
        $this->login()
            ->json('post', '/forms', ['title' => str_repeat('t', 260)])
            ->assertStatus(422)
            ->assertSee('title');
    }

    /** @test */
    public function the_open_date_must_be_before_the_close_date()
    {
        $this->login()
            ->json('post', '/forms', ['title' => 'title', 'open_date' => '1990-02-02', 'close_date' => '1990-02-01'])
            ->assertStatus(422)
            ->assertSee('close_date');
    }

    /** @test */
    public function the_admin_email_field_must_be_a_valid_email_address()
    {
        $this->login()
            ->json('post', '/forms', ['title' => 'title', 'admin_email' => 'bad_email'])
            ->assertStatus(422)
            ->assertSee('admin_email');
    }

    /** @test */
    public function all_emails_in_the_admin_email_field_must_be_valid()
    {
        $this->login()
            ->json('post', '/forms', ['title' => 'title', 'admin_email' => 'valid@email.com;bad_email'])
            ->assertStatus(422)
            ->assertSee('admin_email');
    }

    /** @test */
    public function a_user_can_clear_the_description_field()
    {
        $form = $this->loginUserWithForm();

        $this->patch(formPath($form), ['title' => 'New title', 'description' => ''])
            ->assertStatus(200);

        $this->assertEquals('', $form->fresh()->description);
    }

    /** @test */
    public function an_error_is_returned_if_setting_response_email_field_for_non_existing_question()
    {
        $form = $this->loginUserWithForm();

        $this->patch(formPath($form), ['title' => 'New title', 'response_email_field' => 1])
            ->assertStatus(422)
            ->assertJsonFragment(['error' => 'Question for the response email field not present on form']);
    }

    /** @test */
    public function an_error_is_returned_if_setting_response_email_field_for_question_on_different_form()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => 999]);

        $this->patch(formPath($form), ['title' => 'New title', 'response_email_field' => $question->id])
            ->assertStatus(422)
            ->assertJsonFragment(['error' => 'Question for the response email field not present on form']);
    }
}