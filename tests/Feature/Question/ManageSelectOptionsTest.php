<?php

namespace Tests\Feature\Question;

use App\Form;
use App\Question;
use App\SelectOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageSelectOptionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_edit_a_select_questions_options()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['display_value' => 'Old value', 'question_id' => $question->id]);

        $this->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            [
                'title' => 'New title',
                'type' => 'radio',
                'order' => 2,
                'options' => [['id' => $option->id, 'value' => 'value', 'display_value' => 'New value']]
            ]
        )->assertStatus(200);

        $this->assertEquals('New value', $option->fresh()->display_value);
    }

    /** @test */
    public function a_user_can_add_a_new_option_to_a_select_question()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);

        $this->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            [
                'title' => 'New title',
                'type' => 'radio',
                'order' => 2,
                'options' => [['id' => null, 'value' => 'value', 'display_value' => 'New value']]
            ]
        )->assertStatus(200);

        $this->assertDatabaseHas('select_options', ['display_value' => 'New value']);
    }

    /** @test */
    public function a_user_cant_add_a_new_option_to_another_users_question()
    {
        $form = create(Form::class, ['user_id' => 9999]);
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);

        $this->login()->patch(
            '/forms/' . $question->form->id . '/questions/' . $question->id,
            [
                'title' => 'New title',
                'type' => 'radio',
                'order' => 2,
                'options' => [['id' => null, 'value' => 'value', 'display_value' => 'New value']]
            ]
        )->assertStatus(403);
    }


    /** @test */
    public function a_guest_cant_delete_a_select_option()
    {
        $question = create(Question::class, ['type' => 'radio']);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->delete('/select-options/' . $option->id)
            ->assertRedirect('login');

        $this->assertDatabaseHas('select_options', ['id' => $option->id]);
    }

    /** @test */
    public function a_user_can_delete_an_option_from_a_select_question()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->delete('/select-options/' . $option->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('select_options', ['id' => $option->id]);
    }

    /** @test */
    public function a_user_cant_delete_an_option_for_another_users_form()
    {
        $form = create(Form::class, ['user_id' => 9999]);
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->login()
            ->delete('/select-options/' . $option->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('select_options', ['id' => $option->id]);
    }

    /** @test */
    public function a_user_can_delete_an_option_to_a_form_they_have_update_access_for()
    {
        $form = $this->createFormWithAccess('update');
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->delete('/select-options/' . $option->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('select_options', ['id' => $option->id]);
    }

    /** @test */
    public function a_user_cant_delete_an_option_to_a_form_they_have_view_access_for()
    {
        $form = $this->createFormWithAccess('view');
        $question = create(Question::class, ['type' => 'radio', 'form_id' => $form->id]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->delete('/select-options/' . $option->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('select_options', ['id' => $option->id]);
    }
}