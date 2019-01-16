<?php

namespace Tests\Unit;

use App\Events\ResponseRecorded;
use App\Form;
use App\FormResponse;
use App\Listeners\SendFormRespondedNotification;
use App\Mail\FormResponded;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendFormRespondedNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function doesnt_send_notification_if_no_admin_email_set()
    {
        Mail::fake();

        $form = create(Form::class, ['admin_email' => null]);
        $response = create(FormResponse::class, ['form_id' => $form->id]);

        $event = new ResponseRecorded($response);
        (new SendFormRespondedNotification())->handle($event);

        Mail::assertNotSent(FormResponded::class);
    }
    
    /** @test */
    public function multiple_admin_emails_can_be_used_for_response_email()
    {
        Mail::fake();

        $form = create(Form::class, ['admin_email' => 'admin@email.com;foo@email.com']);
        $response = create(FormResponse::class, ['form_id' => $form->id]);

        $event = new ResponseRecorded($response);
        (new SendFormRespondedNotification())->handle($event);

        Mail::assertSent(FormResponded::class, function ($mail) {
            return $mail->hasTo('admin@email.com');
        });
        Mail::assertSent(FormResponded::class, function ($mail) {
            return $mail->hasTo('foo@email.com');
        });
    }

    /** @test */
    public function emails_are_only_sent_to_valid_email_addresses()
    {
        Mail::fake();

        $form = create(Form::class, ['admin_email' => 'admin@email.com;notvalid']);
        $response = create(FormResponse::class, ['form_id' => $form->id]);

        $event = new ResponseRecorded($response);
        (new SendFormRespondedNotification())->handle($event);

        Mail::assertSent(FormResponded::class, function ($mail) {
            return $mail->hasTo('admin@email.com');
        });
        Mail::assertNotSent(FormResponded::class, function ($mail) {
            return $mail->hasTo('notvalid');
        });
    }
}