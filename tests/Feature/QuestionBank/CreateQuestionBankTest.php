<?php

namespace Tests\Feature\QuestionBank;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateQuestionBankTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_view_the_create_question_bank_page()
    {
        $this->get('/admin/question-bank/create')->assertRedirect('login');
    }

    /** @test */
    public function a_standard_user_cant_view_the_create_question_bank_page()
    {
        $this->login()->get('/admin/question-bank/create')->assertStatus(403);
    }

    /** @test */
    public function an_admin_user_cant_view_the_create_question_bank_page()
    {
        $this->loginAdmin()->get('/admin/question-bank/create')->assertSee('question-bank-form');
    }

    /** @test */
    public function a_guest_cant_create_a_new_question_bank_question()
    {
        $this->post('/admin/question-bank', [])->assertRedirect('login');
    }

    /** @test */
    public function a_non_admin_cant_create_a_new_question_bank_question()
    {
        $this->login()->post('/admin/question-bank', [])->assertStatus(403);
    }

    /** @test */
    public function an_admin_can_create_a_new_question_bank_question()
    {
        $this->loginAdmin()
            ->post('/admin/question-bank', [
                'title' => 'Question title',
                'type' => 'text',
                'help_text' => 'Help text',
                'required' => true,
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('questions', [
            'title' => 'Question title',
            'type' => 'text',
            'help_text' => 'Help text',
            'required' => true,
            'form_id' => null,
            'in_question_bank' => true,
            'order' => 0
        ]);
    }

    /** @test */
    public function an_admin_can_create_a_new_select_question_bank_question()
    {
        $this->loginAdmin()
            ->post('/admin/question-bank', [
                'title' => 'Question title',
                'type' => 'radio',
                'help_text' => 'Help text',
                'required' => true,
                'options' => [
                    ['value' => 'a', 'display_value' => 'Value a']
                ]
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('questions', ['title' => 'Question title']);
        $this->assertDatabaseHas('select_options', ['value' => 'a', 'display_value' => 'Value a']);
    }
}
