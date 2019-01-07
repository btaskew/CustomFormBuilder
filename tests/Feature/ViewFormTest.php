<?php

namespace Tests\Feature;

use App\Form;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_all_their_forms()
    {
        $this->login();
        $form = factory(Form::class)->create(['user_id' => auth()->user()->id]);

        $this->json('get', '/forms')
            ->assertStatus(200)
            ->assertSee($form->title);
    }

    /** @test */
    public function a_user_can_view_a_forms_questions()
    {
        $this->login();
        $form = factory(Form::class)->create(['user_id' => auth()->user()->id]);
        $question = factory(Question::class)->create(['form_id' => $form->id]);

        $this->json('get', '/forms/' . $form->id . '/questions')
            ->assertStatus(200)
            ->assertSee($question->title);
    }
}