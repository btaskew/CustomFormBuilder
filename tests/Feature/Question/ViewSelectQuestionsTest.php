<?php

namespace Tests\Feature\Question;

use App\Form;
use App\Question;
use App\SelectOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewSelectQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_view_select_questions_for_a_form()
    {
        $this->get('/forms/1/select-questions')->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_view_all_the_select_questions_on_their_form()
    {
        $form = $this->loginUserWithForm();
        $selectQuestion = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $selectQuestion->id]);
        $otherQuestion = create(Question::class, ['type' => 'text', 'form_id' => $form->id]);

        $this->get(formPath($form) . '/select-questions')
            ->assertStatus(200)
            ->assertSee($selectQuestion->title)
            ->assertSee($option->value)
            ->assertDontSee($otherQuestion->title);
    }

    /** @test */
    public function a_user_can_exclude_a_particular_question_from_the_results()
    {
        $form = $this->loginUserWithForm();
        $selectQuestion = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $questionToExclude = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);

        $this->get(formPath($form) . '/select-questions?exclude_question=' . $questionToExclude->id)
            ->assertStatus(200)
            ->assertSee($selectQuestion->title)
            ->assertDontSee($questionToExclude->title);
    }
    
    /** @test */
    public function a_user_cant_view_select_questions_for_another_users_form()
    {
        $form = create(Form::class, ['user_id' => 999]);
        $selectQuestion = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);

        $this->login()
            ->get(formPath($form) . '/select-questions')
            ->assertStatus(403)
            ->assertDontSee($selectQuestion->title);
    }

    /** @test */
    public function a_user_can_view_select_questions_for_a_form_they_have_edit_access_to()
    {
        $form = $this->createFormWithAccess('edit');
        $selectQuestion = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);

        $this->get(formPath($form) . '/select-questions')
            ->assertStatus(200)
            ->assertSee($selectQuestion->title);
    }

    /** @test */
    public function a_user_cant_view_select_questions_for_a_form_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');
        $selectQuestion = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);

        $this->get(formPath($form) . '/select-questions')
            ->assertStatus(403)
            ->assertDontSee($selectQuestion->title);
    }
}