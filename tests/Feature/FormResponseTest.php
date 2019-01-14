<?php

namespace Tests\Feature;

use App\Form;
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
}