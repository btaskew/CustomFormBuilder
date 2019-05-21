<?php

namespace Tests\Unit;

use App\Form;
use App\FormResponse;
use App\Http\Requests\ResponseRequest;
use App\Question;
use App\Services\FormResponder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormResponderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function no_data_is_stored_for_unanswerable_questions()
    {
        $form = create(Form::class);
        $question = create(Question::class, ['form_id' => $form->id]);
        $labelQuestion = create(Question::class, ['form_id' => $form->id, 'type' => 'label']);

        $request = new ResponseRequest([$question->id => 'value', $labelQuestion->id => 'label value']);

        (new FormResponder())->saveResponse($request, $form);

        $response = FormResponse::latest()->first();
        $this->assertEquals([$question->id => 'value'], $response->response);
    }

    /** @test */
    public function a_date_field_is_converted_from_unix()
    {
        $form = create(Form::class);
        $question = create(Question::class, ['form_id' => $form->id, 'type' => 'date']);

        $request = new ResponseRequest([$question->id => 631152000000]);

        (new FormResponder())->saveResponse($request, $form);

        $response = FormResponse::latest()->first();
        $this->assertEquals([$question->id => '1990-01-01'], $response->response);
    }
}
