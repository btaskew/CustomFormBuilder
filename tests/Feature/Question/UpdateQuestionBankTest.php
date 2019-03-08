<?php

namespace Tests\Feature\Question;

use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateQuestionBankTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_update_a_question_bank_question()
    {
        $this->patch('/admin/question-bank/1', [])->assertRedirect('login');
    }

    /** @test */
    public function a_non_admin_cant_update_a_question_bank_question()
    {
        $question = create(Question::class);
        $this->login()->patch('/admin/question-bank/' . $question->id, [])->assertRedirect('login');
    }

    /** @test */
    public function an_admin_can_update_a_question_bank_question()
    {
        $question = create(Question::class, ['form_id' => null, 'in_question_bank' => true, 'type' => 'text']);

        $this->loginAdmin()
            ->patch('/admin/question-bank/' . $question->id, ['title' => 'New title', 'type' => 'password'])
            ->assertStatus(200);

        $this->assertEquals('New title', $question->fresh()->title);
        $this->assertEquals('password', $question->fresh()->type);
    }

    /** @test */
    public function a_non_bank_question_cant_be_updated_via_this_route()
    {
        $question = create(Question::class, ['form_id' => 1, 'in_question_bank' => false, 'type' => 'text']);

        $this->loginAdmin()
            ->patch('/admin/question-bank/' . $question->id, ['title' => 'New title', 'type' => 'password'])
            ->assertStatus(403);

        $this->assertEquals($question->title, $question->fresh()->title);
    }
}
