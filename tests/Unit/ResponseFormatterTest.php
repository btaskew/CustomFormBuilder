<?php

namespace Tests\Unit;

use App\Form;
use App\FormResponse;
use App\Objects\FormattedResponse;
use App\Question;
use App\Services\ResponseFormatter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResponseFormatterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_correctly_maps_a_forms_responses()
    {
        $form = create(Form::class);
        $questions = create(Question::class, ['form_id' => $form->id], 2);
        $response1 = create(FormResponse::class, [
            'form_id' => $form->id,
            'response' => [$questions[0]->id => 'r1q1', $questions[1]->id => 'r1q2']
        ]);
        $response2 = create(FormResponse::class, [
            'form_id' => $form->id,
            'response' => [$questions[0]->id => 'r2q1', $questions[1]->id => 'r2q2']
        ]);

        $expectedResponse1 = (new FormattedResponse())
            ->setId($response1->id)
            ->setTimeRecorded($response1->created_at)
            ->addAnswer($questions[0]->getFullTitle(), 'r1q1')
            ->addAnswer($questions[1]->getFullTitle(), 'r1q2');

        $expectedResponse2 = (new FormattedResponse())
            ->setId($response2->id)
            ->setTimeRecorded($response2->created_at)
            ->addAnswer($questions[0]->getFullTitle(), 'r2q1')
            ->addAnswer($questions[1]->getFullTitle(), 'r2q2');

        $expectedResult = [
            $response1->id => $expectedResponse1,
            $response2->id => $expectedResponse2,
        ];

        $this->assertEquals(
            $expectedResult,
            (new ResponseFormatter())
                ->setQuestions($form->getOrderedQuestions())
                ->formatResponses([$response1, $response2])
        );
    }

    /** @test */
    public function it_correctly_maps_a_forms_responses_if_one_question_is_not_answered()
    {
        $form = create(Form::class);
        $questions = create(Question::class, ['form_id' => $form->id], 2);
        $response = create(FormResponse::class, [
            'form_id' => $form->id,
            'response' => [$questions[0]->id => 'r1q1']
        ]);

        $expectedResponse1 = (new FormattedResponse())
            ->setId($response->id)
            ->setTimeRecorded($response->created_at)
            ->addAnswer($questions[0]->getFullTitle(), 'r1q1')
            ->addBlankAnswer($questions[1]->getFullTitle());

        $this->assertEquals(
            [$response->id => $expectedResponse1],
            (new ResponseFormatter())
                ->setQuestions($form->getOrderedQuestions())
                ->formatResponses([$response])
        );
    }
}
