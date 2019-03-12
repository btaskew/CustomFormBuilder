<?php

namespace Tests\Feature\Question;

use App\Form;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewQuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_view_a_forms_questions()
    {
        $this->get('/forms/1/questions')->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_view_a_forms_questions()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->get(formPath($form) . '/questions')
            ->assertStatus(200)
            ->assertSee($question->title);
    }

    /** @test */
    public function a_user_cant_view_questions_on_another_users_form()
    {
        $form = create(Form::class, ['user_id' => 999]);
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->login()
            ->get(formPath($form) . '/questions')
            ->assertStatus(403)
            ->assertDontSee($question->title);
    }

    /** @test */
    public function a_user_can_view_questions_to_a_form_they_have_update_access_to()
    {
        $form = $this->createFormWithAccess('update');
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->get(formPath($form) . '/questions')
            ->assertStatus(200)
            ->assertSee($question->title);
    }

    /** @test */
    public function a_user_cant_view_questions_to_a_form_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->get(formPath($form) . '/questions')
            ->assertStatus(403)
            ->assertDontSee($question->title);
    }


    /** @test */
    public function a_guest_cant_view_a_specific_questions_data()
    {
        $this->get('/forms/1/questions/1')->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_view_a_questions_data_for_one_of_their_forms()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['title' => 'Old title', 'form_id' => $form->id]);

        $this->get(formPath($form) . '/questions/' . $question->id)
            ->assertStatus(200)
            ->assertSee($question->title);
    }

    /** @test */
    public function a_user_cant_view_a_questions_data_for_someone_elses_form()
    {
        $form = create(Form::class, ['user_id' => 999]);
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->login()
            ->get(formPath($form) . '/questions/' . $question->id)
            ->assertStatus(403)
            ->assertDontSee($question->title);
    }

    /** @test */
    public function a_user_can_view_a_questions_data_for_a_form_they_have_update_access_to()
    {
        $form = $this->createFormWithAccess('update');
        $question = create(Question::class, ['title' => 'Old title', 'form_id' => $form->id]);

        $this->get(formPath($form) . '/questions/' . $question->id)
            ->assertStatus(200)
            ->assertSee($question->title);
    }

    /** @test */
    public function a_user_cant_view_a_questions_data_for_a_form_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');
        $question = create(Question::class, ['title' => 'Old title', 'form_id' => $form->id]);

        $this->get(formPath($form) . '/questions/' . $question->id)
            ->assertStatus(403)
            ->assertDontSee($question->title);
    }
}