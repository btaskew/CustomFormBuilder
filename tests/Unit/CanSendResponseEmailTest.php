<?php

namespace Tests\Unit;

use App\Form;
use App\FormResponse;
use App\Question;
use App\Specifications\CanSendResponseEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanSendResponseEmailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function returns_false_if_form_has_no_response_email()
    {
        $form = create(Form::class, ['response_email' => null, 'response_email_field' => 1]);

        $this->assertFalse(CanSendResponseEmail::isSatisfiedBy($form, new FormResponse()));
    }

    /** @test */
    public function returns_false_if_form_has_no_response_email_field()
    {
        $form = create(Form::class, ['response_email' => 'text', 'response_email_field' => null]);

        $this->assertFalse(CanSendResponseEmail::isSatisfiedBy($form, new FormResponse()));
    }

    /** @test */
    public function returns_false_if_no_response_given_for_relevant_question()
    {
        $form = create(Form::class, ['response_email' => 'text', 'response_email_field' => 1]);
        $response = create(FormResponse::class, ['response' => '{"1":null}']);

        $this->assertFalse(CanSendResponseEmail::isSatisfiedBy($form, $response));
    }

    /** @test */
    public function returns_false_if_invalid_response_given_for_relevant_question()
    {
        $form = create(Form::class, ['response_email' => 'text', 'response_email_field' => 1]);
        $response = create(FormResponse::class, ['response' => '{"1":"not-an-email"}']);

        $this->assertFalse(CanSendResponseEmail::isSatisfiedBy($form, $response));
    }
}