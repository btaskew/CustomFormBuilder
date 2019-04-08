<?php

namespace Tests\Unit;

use App\Contracts\ResponseFormatter;
use App\Form;
use App\FormResponse;
use App\Mail\FormResponded;
use App\Objects\FormattedResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormRespondedMailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function form_responded_mail_builds_correctly()
    {
        $form = create(Form::class);
        $response = create(FormResponse::class);
        $formattedResponse = new FormattedResponse();

        $formatter = \Mockery::mock(ResponseFormatter::class);
        $formatter->shouldReceive('setQuestions')->andReturnSelf();
        $formatter->shouldReceive('formatResponses')->with([$response])->andReturn([$response->id => $formattedResponse]);
        $this->app->instance(ResponseFormatter::class, $formatter);

        $mail = (new FormResponded($form, $response))->build();

        $this->assertEquals($form->title . ' - response recorded', $mail->subject);
        $this->assertContains('no-reply@exeter.ac.uk', $mail->from[0]);
        $this->assertEquals(['response' => $formattedResponse, 'form' => $form], $mail->viewData);
    }
}
