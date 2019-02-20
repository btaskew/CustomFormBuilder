<?php

namespace Tests\Feature;

use App\Form;
use App\FormUser;
use App\Question;
use App\SelectOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_all_their_forms()
    {
        $form = $this->loginUserWithForm();
        $form2 = create(Form::class, ['user_id' => 9999]);
        create(FormUser::class, [
            'user_id' => $form->user_id,
            'form_id' => $form2->id
        ]);

        $this->json('get', '/forms')
            ->assertStatus(200)
            ->assertSee($form->title)
            ->assertSee($form2->title);
    }

    /** @test */
    public function a_user_can_view_a_forms_questions()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->json('get', formPath($form) . '/questions')
            ->assertStatus(200)
            ->assertSee($question->title);
    }

    /** @test */
    public function a_user_cant_view_questions_on_another_users_form()
    {
        $this->withExceptionHandling();

        $this->login();
        $form = create(Form::class, ['user_id' => 999]);
        create(Question::class, ['form_id' => $form->id]);

        $this->get(formPath($form) . '/questions')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_preview_their_complete_form()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->get(formPath($form) . '/preview')
            ->assertStatus(200)
            ->assertSee($form->title)
            ->assertSee($question->title);
    }

    /** @test */
    public function anyone_can_view_a_complete_form()
    {
        $form = create(Form::class);
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->get(formPath($form))
            ->assertStatus(200)
            ->assertSee($form->title)
            ->assertSee($question->title);
    }

    /** @test */
    public function anyone_can_view_a_complete_form_with_a_select_question()
    {
        $form = create(Form::class);
        $question = create(Question::class, ['form_id' => $form->id, 'type' => 'radio']);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->get(formPath($form))
            ->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($option->display_value);
    }

    /** @test */
    public function an_inactive_form_is_not_viewable()
    {
        $form = create(Form::class, ['active' => false]);

        $this->get(formPath($form))
            ->assertStatus(200)
            ->assertSee('This form is not currently active');
    }
}