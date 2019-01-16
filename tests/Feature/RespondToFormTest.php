<?php

namespace Tests\Feature;

use App\Form;
use App\FormResponse;
use App\Mail\FormResponded;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RespondToFormTest extends TestCase
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
    
    /** @test */
    public function an_email_is_sent_to_the_form_administrator_if_set_when_a_response_is_recorded()
    {
        Mail::fake();

        $form = create(Form::class, ['admin_email' => 'admin@email.com']);

        $this->post('/forms/' . $form->id . '/responses', [1 => "value"])
            ->assertStatus(200);

        Mail::assertSent(FormResponded::class, function ($mail) {
            return $mail->hasTo('admin@email.com');
        });
    }
}