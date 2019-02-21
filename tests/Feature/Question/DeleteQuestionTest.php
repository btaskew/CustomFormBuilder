<?php

namespace Tests\Feature\Question;

use App\Form;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteQuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_delete_a_question()
    {
        $form = create(Form::class);
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->delete('/forms/' . $question->form->id . '/questions/' . $question->id)
            ->assertRedirect('login');

        $this->assertDatabaseHas('questions', ['id' => $question->id]);
    }

    /** @test */
    public function a_user_can_delete_a_question()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->delete('/forms/' . $question->form->id . '/questions/' . $question->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }

    /** @test */
    public function a_user_cant_delete_a_question_on_another_users_form()
    {
        $form = create(Form::class, ['user_id' => 9999]);
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->login()
            ->delete('/forms/' . $question->form->id . '/questions/' . $question->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('questions', ['id' => $question->id]);
    }

    /** @test */
    public function a_user_can_delete_a_question_on_a_form_they_have_edit_access_to()
    {
        $form = $this->createFormWithAccess('edit');
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->delete('/forms/' . $question->form->id . '/questions/' . $question->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }

    /** @test */
    public function a_user_cant_delete_a_question_on_a_form_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->delete('/forms/' . $question->form->id . '/questions/' . $question->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('questions', ['id' => $question->id]);
    }
}