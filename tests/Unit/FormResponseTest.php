<?php

namespace Tests\Unit;

use App\Form;
use App\FormResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormResponseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_response_belongs_to_a_form()
    {
        $form = create(Form::class);
        $response = create(FormResponse::class, ['form_id' => $form->id]);

        $this->assertEquals($form->id, $response->form->id);
    }
}