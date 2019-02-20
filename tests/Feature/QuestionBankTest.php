<?php

namespace Tests\Feature;

use App\Form;
use App\Question;
use App\SelectOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionBankTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_all_the_questions_in_the_question_bank()
    {
        $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => null, 'in_question_bank' => true]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->get('/forms/1/questions/bank')
            ->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($option->value);
    }

    /** @test */
    public function a_user_can_add_a_question_bank_question_to_their_form()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => null, 'in_question_bank' => true]);

        $this->post(formPath($form) . '/questions/bank', ['questions' => [$question->id]])
            ->assertStatus(200);

        $this->assertEquals($question->title, $form->questions->first()->title);
        $this->assertFalse($form->questions->first()->in_question_bank);
    }

    /** @test */
    public function a_user_can_add_a_select_question_bank_question_to_their_form_with_its_options()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['type' => 'radio', 'form_id' => null, 'in_question_bank' => true]);
        $option = create(SelectOption::class, ['question_id' => $question]);

        $this->post(formPath($form) . '/questions/bank', ['questions' => [$question->id]])
            ->assertStatus(200);

        $this->assertEquals($question->title, $form->questions->first()->title);
        $this->assertEquals($option->value, $form->questions->first()->options->first()->value);
    }

    /** @test */
    public function a_user_cant_add_a_question_bank_question_to_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class);
        $question = create(Question::class, ['form_id' => null, 'in_question_bank' => true]);

        $this->login()
            ->post(formPath($form) . '/questions/bank', ['questions' => [$question->id]])
            ->assertStatus(403);

        $this->assertFalse($form->questions->contains('title', $question->title));
    }

    /** @test */
    public function a_user_can_search_for_a_question_in_the_question_bank()
    {
        $question = create(Question::class, ['form_id' => null, 'in_question_bank' => true]);

        $this->login()
            ->get('/questions/bank/search?title=' . $question->title)
            ->assertStatus(200)
            ->assertSee($question->type);
    }

    /** @test */
    public function search_input_is_sanitised_before_searching()
    {
        $question = create(Question::class, ['title' => 'title', 'form_id' => null, 'in_question_bank' => true]);

        // If the input is sanitised then the search will find the record
        $this->login()
            ->get('/questions/bank/search?title=<h1>title&blahblah</h1>')
            ->assertStatus(200)
            ->assertSee($question->type);
    }

    /** @test */
    public function the_search_results_size_is_limited()
    {
        create(Question::class, ['title' => 'title', 'form_id' => null, 'in_question_bank' => true], 30);

        $result = $this->login()->get('/questions/bank/search?title=title')->json();

        $this->assertCount(25, $result);
    }

}