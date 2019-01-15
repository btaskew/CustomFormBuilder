<?php

namespace Tests\Unit;

use App\Form;
use App\FormResponse;
use App\Mappers\ResponseMapper;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResponseMapperTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_correctly_maps_a_forms_responses()
    {
        $form = create(Form::class);
        $questions = create(Question::class, ['form_id' => $form->id], 2);
        $response1 = create(FormResponse::class, [
            'form_id' => $form->id,
            'response' => '{"' . $questions[0]->id . '":"r1q1","' . $questions[1]->id . '":"r1q2"}'
        ]);
        $response2 = create(FormResponse::class, [
            'form_id' => $form->id,
            'response' => '{"' . $questions[0]->id . '":"r2q1","' . $questions[1]->id . '":"r2q2"}'
        ]);

        $expectedResult = [
            $response1->id => [
                "id" => $response1->id,
                "created_at" => $response1->created_at,
                "answers" => [
                    $questions[0]->id => "r1q1",
                    $questions[1]->id => "r1q2",
                ]
            ],
            $response2->id => [
                "id" => $response2->id,
                "created_at" => $response2->created_at,
                "answers" => [
                    $questions[0]->id => "r2q1",
                    $questions[1]->id => "r2q2",
                ]
            ],
        ];

        $this->assertEquals($expectedResult, (new ResponseMapper($form))->map());
    }

    /** @test */
    public function it_correctly_maps_a_forms_responses_if_one_question_is_not_answered()
    {
        $form = create(Form::class);
        $questions = create(Question::class, ['form_id' => $form->id], 2);
        $response = create(FormResponse::class, [
            'form_id' => $form->id,
            'response' => '{"' . $questions[0]->id . '":"r1q1"}'
        ]);

        $expectedResult = [
            $response->id => [
                "id" => $response->id,
                "created_at" => $response->created_at,
                "answers" => [
                    $questions[0]->id => "r1q1",
                    $questions[1]->id => "n/a",
                ]
            ],
        ];

        $this->assertEquals($expectedResult, (new ResponseMapper($form))->map());
    }
}