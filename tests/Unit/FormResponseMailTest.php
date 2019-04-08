<?php

namespace Tests\Unit;

use App\Mail\FormResponse;
use PHPUnit\Framework\TestCase;

class FormResponseMailTest extends TestCase
{
    /** @test */
    public function form_response_mail_builds_correctly()
    {
        $mail = (new FormResponse('Mail body'))->build();

        $this->assertContains('no-reply@exeter.ac.uk', $mail->from[0]);
        $this->assertEquals(['body' => 'Mail body'], $mail->viewData);
    }
}
