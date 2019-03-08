<?php

namespace Tests\Feature\QuestionBank;

use App\Question;
use App\SelectOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewQuestionBankTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_view_question_bank_questions()
    {
        $this->get('/forms/1/questions/bank')->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_view_all_the_questions_in_the_question_bank()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => null, 'in_question_bank' => true]);
        $option = create(SelectOption::class, ['question_id' => $question->id]);

        $this->get(formPath($form) . '/question-bank')
            ->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($option->value);
    }

    /** @test */
    public function a_guest_cant_search_for_a_question_in_the_question_bank()
    {
        $this->get('/question-bank/search?title=foo')->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_search_for_a_question_in_the_question_bank()
    {
        $question = create(Question::class, ['form_id' => null, 'in_question_bank' => true]);

        $this->login()
            ->get('/question-bank/search?title=' . $question->title)
            ->assertStatus(200)
            ->assertSee($question->type);
    }

    /** @test */
    public function search_input_is_sanitised_before_searching()
    {
        $question = create(Question::class, ['title' => 'title', 'form_id' => null, 'in_question_bank' => true]);

        // If the input is sanitised then the search will find the record
        $this->login()
            ->get('/question-bank/search?title=<h1>title&blahblah</h1>')
            ->assertStatus(200)
            ->assertSee($question->type);
    }

    /** @test */
    public function the_search_results_size_is_limited()
    {
        create(Question::class, ['title' => 'title', 'form_id' => null, 'in_question_bank' => true], 30);

        $result = $this->login()->get('/question-bank/search?title=title')->json();

        $this->assertCount(25, $result);
    }


    /** @test */
    public function an_admin_can_view_the_question_bank_via_the_admin_route()
    {
        $question = create(Question::class, ['form_id' => null, 'in_question_bank' => true]);

        $this->loginAdmin()
            ->get('/admin/question-bank')
            ->assertStatus(200)
            ->assertSee($question->title);
    }
}
