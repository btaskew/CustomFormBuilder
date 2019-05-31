<?php

namespace Tests\Unit;

use App\FormResponse;
use App\Services\FormResponseMailMapper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormResponseMailMapperTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_replaces_question_keys_in_message_for_their_responses()
    {
        $mapper = new FormResponseMailMapper();
        $response = create(FormResponse::class, [
            'response' => [1 => 'Response 1', 2 => 'Response 2']
        ]);

        $emailText = '[1] [2]';

        $this->assertEquals('Response 1 Response 2', $mapper->mapResponse($emailText, $response));
    }


    /** @test */
    public function it_replaces_keys_for_questions_that_have_no_answer_with_nothing()
    {
        $mapper = new FormResponseMailMapper();
        $response = create(FormResponse::class, [
            'response' => [1 => 'Response 1']
        ]);

        $emailText = '[1] [2]';

        $this->assertEquals(
            'Response 1 ',
            $mapper->mapResponse($emailText, $response)
        );
    }
}
