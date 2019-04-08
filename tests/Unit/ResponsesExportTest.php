<?php

namespace Tests\Unit;

use App\Contracts\ResponseFormatter;
use App\Exports\ResponsesExport;
use App\Form;
use App\Objects\FormattedResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResponsesExportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function export_returns_view_with_correct_data()
    {
        $form = create(Form::class);
        $formattedResponse = new FormattedResponse();

        $formatter = \Mockery::mock(ResponseFormatter::class);
        $formatter->shouldReceive('setQuestions')->andReturnSelf();
        $formatter->shouldReceive('formatResponses')->andReturn([$formattedResponse]);

        $view = (new ResponsesExport($form, $formatter))->view();

        $this->assertEquals(
            ['responses' => [$formattedResponse], 'questions' => $form->getAnswerableQuestions()],
            $view->getData()
        );
    }
}
