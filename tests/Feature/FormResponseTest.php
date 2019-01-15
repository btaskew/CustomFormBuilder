<?php

namespace Tests\Feature;

use App\Form;
use App\FormResponse;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormResponseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_submitted_forms_data_is_stored_correctly()
    {
        $form = create(Form::class);
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->post('/forms/' . $form->id . '/responses', [$question->id => "value"])
            ->assertStatus(200);

        $this->assertDatabaseHas('form_responses', [
            'form_id' => $form->id,
            'response' => '{"' . $question->id . '":"value"}'
        ]);
    }

    /** @test */
    public function a_user_can_view_their_forms_responses()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);
        create(FormResponse::class, [
            'form_id' => $form->id,
            'response' => '{"' . $question->id . '":"value"}'
        ]);

        $this->get('/forms/' . $form->id . '/responses')
            ->assertStatus(200)
            ->assertSee("value");
    }
}