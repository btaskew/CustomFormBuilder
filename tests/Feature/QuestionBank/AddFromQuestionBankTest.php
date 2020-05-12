<?php

namespace Tests\Feature\QuestionBank;

use App\Form;
use App\Question;
use App\SelectOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddFromQuestionBankTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_add_a_question_bank_question_to_their_form()
    {
        $this->withoutExceptionHandling();
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => null, 'in_question_bank' => true]);

        $this->post('/question-bank/assign', ['form' => $form->id, 'questions' => [$question->id]])
            ->assertStatus(200);

        $this->assertEquals($question->title, $form->questions->first()->title);
        $this->assertFalse($form->questions->first()->in_question_bank);
    }

    /** @test */
    public function a_guest_cant_add_a_question_bank_question_to_a_form()
    {
        $this->post('question-bank/assign', ['form' => 1, 'questions' => [1]])->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_add_a_select_question_bank_question_to_their_form_with_its_options()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['type' => 'radio', 'form_id' => null, 'in_question_bank' => true]);
        $option = create(SelectOption::class, ['question_id' => $question]);

        $this->post('/question-bank/assign', ['form' => $form->id, 'questions' => [$question->id]])
            ->assertStatus(200);

        $this->assertEquals($question->title, $form->questions->first()->title);
        $this->assertEquals($option->value, $form->questions->first()->options->first()->value);
    }

    /** @test */
    public function a_user_cant_add_a_question_bank_question_to_another_users_form()
    {
        $form = create(Form::class);
        $question = create(Question::class, ['form_id' => null, 'in_question_bank' => true]);

        $this->login()
            ->post('/question-bank/assign', ['form' => $form->id, 'questions' => [$question->id]])
            ->assertStatus(403);

        $this->assertFalse($form->questions->contains('title', $question->title));
    }

    /** @test */
    public function a_user_can_add_a_question_bank_question_to_a_form_they_have_update_access_to()
    {
        $form = $this->createFormWithAccess('update');
        $question = create(Question::class, ['form_id' => null, 'in_question_bank' => true]);

        $this->post('/question-bank/assign', ['form' => $form->id, 'questions' => [$question->id]])
            ->assertStatus(200);

        $this->assertEquals($question->title, $form->questions->first()->title);
    }

    /** @test */
    public function a_user_cant_add_a_question_bank_question_to_a_form_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');
        $question = create(Question::class, ['form_id' => null, 'in_question_bank' => true]);

        $this->post('/question-bank/assign', ['form' => $form->id, 'questions' => [$question->id]])
            ->assertStatus(403);

        $this->assertFalse($form->questions->contains('title', $question->title));
    }
}
