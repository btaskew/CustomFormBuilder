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

        $this->post(formPath($form) . '/responses', [$question->id => "value"])
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

        $this->get(formPath($form) . '/responses')
            ->assertStatus(200)
            ->assertSee("value");
    }

    /** @test */
    public function a_label_field_stores_no_data()
    {
        $form = create(Form::class);
        $question = create(Question::class, ['form_id' => $form->id]);
        $labelQuestion = create(Question::class, ['form_id' => $form->id, 'type' => 'label']);

        $this->post(formPath($form) . '/responses', [$question->id => "value", $labelQuestion->id => "label value"])
            ->assertStatus(200);

        $this->assertDatabaseHas('form_responses', [
            'form_id' => $form->id,
            'response' => '{"' . $question->id . '":"value"}'
        ]);
    }

    /** @test */
    public function viewing_form_responses_doesnt_show_label_fields()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);
        $labelQuestion = create(Question::class, ['form_id' => $form->id, 'type' => 'label']);

        create(FormResponse::class, [
            'form_id' => $form->id,
            'response' => '{"' . $question->id . '":"value"}'
        ]);

        $this->get(formPath($form) . '/responses')
            ->assertStatus(200)
            ->assertDontSee($labelQuestion->title);
    }

    /** @test */
    public function an_email_is_sent_to_the_form_administrator_if_set_when_a_response_is_recorded()
    {
        Mail::fake();

        $form = create(Form::class, ['admin_email' => 'admin@email.com']);

        $this->post(formPath($form) . '/responses', [1 => "value"])
            ->assertStatus(200);

        Mail::assertSent(FormResponded::class, function ($mail) {
            return $mail->hasTo('admin@email.com');
        });
    }

    /** @test */
    public function an_email_is_sent_to_the_responder_if_the_form_allows()
    {
        Mail::fake();

        $form = create(Form::class, ['response_email' => 'Thanks for responding!', 'response_email_field' => 1]);
        create(Question::class, ['form_id' => $form->id]);

        $this->post(formPath($form) . '/responses', [1 => "test@email.com"])
            ->assertStatus(200);

        Mail::assertSent(\App\Mail\FormResponse::class, function ($mail) {
            return $mail->hasTo('test@email.com');
        });
    }

    /** @test */
    public function responses_cant_be_made_against_inactive_forms()
    {
        $form = create(Form::class, ['active' => false]);
        $question = create(Question::class, ['form_id' => $form->id]);

        $this->post(formPath($form) . '/responses', [$question->id => "value"])
            ->assertStatus(403);

        $this->assertDatabaseMissing('form_responses', [
            'form_id' => $form->id,
            'response' => '{"' . $question->id . '":"value"}'
        ]);
    }
}