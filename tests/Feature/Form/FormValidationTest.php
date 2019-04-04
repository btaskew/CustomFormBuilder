<?php

namespace Tests\Feature\Form;

use App\Form;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_form_title_is_required()
    {
        $this->postForm(['title' => null])
            ->assertStatus(422)
            ->assertSee('title');
    }

    /** @test */
    public function a_folder_id_is_required()
    {
        $this->postForm(['folder_id' => null])
            ->assertStatus(422)
            ->assertSee('folder_id');
    }

    /** @test */
    public function an_active_status_is_required()
    {
        $this->postForm(['active' => null])
            ->assertStatus(422)
            ->assertSee('active');
    }

    /** @test */
    public function a_form_title_cant_be_too_long()
    {
        $this->postForm(['title' => str_repeat('t', 260)])
            ->assertStatus(422)
            ->assertSee('title');
    }

    /** @test */
    public function the_open_date_must_be_before_the_close_date()
    {
        $this->postForm(['open_date' => '1990-02-02', 'close_date' => '1990-02-01'])
            ->assertStatus(422)
            ->assertSee('close_date');
    }

    /** @test */
    public function the_admin_email_field_must_be_a_valid_email_address()
    {
        $this->postForm(['admin_email' => 'bad_email'])
            ->assertStatus(422)
            ->assertSee('admin_email');
    }

    /** @test */
    public function all_emails_in_the_admin_email_field_must_be_valid()
    {
        $this->postForm(['admin_email' => 'valid@email.com;bad_email'])
            ->assertStatus(422)
            ->assertSee('admin_email');
    }

    /** @test */
    public function a_user_can_clear_the_description_field()
    {
        $this->updateForm(['description' => ''])->assertStatus(200);

        $this->assertEquals('', Form::first()->description);
    }

    /** @test */
    public function an_error_is_returned_if_setting_response_email_field_for_non_existing_question()
    {
        $this->updateForm(['response_email_field' => 1])
            ->assertStatus(422)
            ->assertJsonFragment(['error' => 'Question for the response email field not present on form']);
    }

    /** @test */
    public function an_error_is_returned_if_setting_response_email_field_for_question_on_different_form()
    {
        $question = create(Question::class, ['form_id' => 999]);

        $this->updateForm(['response_email_field' => $question->i])
            ->assertStatus(422)
            ->assertJsonFragment(['error' => 'Question for the response email field not present on form']);
    }

    private function postForm(array $attributes = [])
    {
        $defaultAttributes = [
            'title' => 'Title',
            'folder_id' => 1,
            'active' => true
        ];

        return $this->login()->json('POST', '/forms', array_merge($defaultAttributes, $attributes));
    }

    /**
     * @param array $attributes
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function updateForm(array $attributes = [])
    {
        $defaultAttributes = [
            'title' => 'Title',
            'folder_id' => 1,
            'active' => true
        ];

        $form = $this->loginUserWithForm();

        return $this->patch(formPath($form), array_merge($defaultAttributes, $attributes));
    }
}