<?php

namespace Tests\Feature\Question;

use App\Form;
use App\Question;
use App\SelectOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateQuestionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array
     */
    private $attributes = [
        'title' => 'First question',
        'type' => 'text',
        'help_text' => 'Help text',
        'required' => true,
    ];

    /** @test */
    public function a_user_can_view_the_create_question_page_for_their_form()
    {
        $form = $this->loginUserWithForm();

        $this->get(formPath($form) . '/questions/create')->assertSee('Create form question');
    }

    /** @test */
    public function a_guest_cant_view_the_create_question_page()
    {
        $this->get('/forms/1/questions/create')->assertRedirect('login');
    }

    /** @test */
    public function a_user_cant_view_the_create_question_page_for_another_users_form()
    {
        $form = create(Form::class, ['user_id' => 999]);

        $this->login()->get(formPath($form) . '/questions/create')->assertStatus(403);
    }

    /** @test */
    public function a_user_can_view_the_create_question_page_for_another_users_form_they_have_update_access_to()
    {
        $form = $this->createFormWithAccess('update');

        $this->get(formPath($form) . '/questions/create')->assertSee('Create form question');
    }

    /** @test */
    public function a_user_cant_view_the_create_question_page_for_another_users_form_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');

        $this->login()->get(formPath($form) . '/questions/create')->assertStatus(403);
    }


    /** @test */
    public function a_user_can_create_questions()
    {
        $form = $this->loginUserWithForm();

        $this->post(formPath($form) . '/questions', $this->attributes)->assertStatus(200);

        $this->assertDatabaseHas('questions', $this->attributes);
    }

    /** @test */
    public function a_guest_cant_add_questions()
    {
        $this->post('/forms/1/questions', [])->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_select_question()
    {
        $form = $this->loginUserWithForm();

        $attributes = [
            'title' => 'First question',
            'type' => 'radio',
            'help_text' => 'Help text',
            'required' => true,
            'options' => [
                ['value' => 'a', 'display_value' => 'Value a']
            ]
        ];

        $this->post(formPath($form) . '/questions', $attributes)->assertStatus(200);

        $this->assertDatabaseHas('questions', ['title' => 'First question']);
        $this->assertDatabaseHas('select_options', ['value' => 'a', 'display_value' => 'Value a']);
    }

    /** @test */
    public function a_user_can_create_a_question_with_a_visibility_requirement()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $attributes = [
            'title' => 'First question',
            'type' => 'text',
            'help_text' => 'Help text',
            'required' => true,
            'visibility_requirement' => true,
            'required_question' => $question->id,
            'required_value' => $option->value
        ];

        $this->post(formPath($form) . '/questions', $attributes)->assertStatus(200);

        $this->assertDatabaseHas('questions', ['title' => 'First question']);
        $this->assertDatabaseHas('visibility_requirements', ['required_value' => $option->value]);
    }

    /** @test */
    public function a_user_cant_create_questions_to_another_users_form()
    {
        $form = create(Form::class, ['user_id' => 9999]);

        $this->login()
            ->post(formPath($form) . '/questions', $this->attributes)
            ->assertStatus(403);

        $this->assertDatabaseMissing('questions', $this->attributes);
    }

    /** @test */
    public function a_user_can_create_questions_for_another_users_form_they_have_update_access_to()
    {
        $form = $this->createFormWithAccess('update');

        $this->post(formPath($form) . '/questions', $this->attributes)->assertStatus(200);

        $this->assertDatabaseHas('questions', $this->attributes);
    }

    /** @test */
    public function a_user_cant_create_questions_for_another_users_form_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');

        $this->post(formPath($form) . '/questions', $this->attributes)->assertStatus(403);

        $this->assertDatabaseMissing('questions', $this->attributes);
    }
}
