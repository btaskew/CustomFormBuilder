<?php

namespace Tests\Unit;

use App\Form;
use App\Question;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Form
     */
    private $form;

    public function setUp()
    {
        parent::setUp();

        $this->form = factory(Form::class)->create();
    }

    /** @test */
    public function a_form_has_an_owner()
    {
        $this->assertInstanceOf(User::class, $this->form->owner);
    }

    /** @test */
    public function a_form_has_questions()
    {
        $question = factory(Question::class)->create([
            'form_id' => $this->form->id
        ]);

        $this->assertEquals($question->form_id, $this->form->questions->first()->id);
    }
}