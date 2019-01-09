<?php

namespace Tests\Unit;

use App\Form;
use App\Question;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Kris\LaravelFormBuilder\Facades\FormBuilder;
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

        $this->form = create(Form::class);
    }

    /** @test */
    public function a_form_has_an_owner()
    {
        $this->assertInstanceOf(User::class, $this->form->owner);
    }

    /** @test */
    public function a_form_has_questions()
    {
        $question = create(Question::class, ['form_id' => $this->form->id]);

        $this->assertEquals($question->form_id, $this->form->questions->first()->id);
    }

    /** @test */
    public function a_forms_questions_are_deleted_when_the_form_is_deleted()
    {
        $question = create(Question::class, ['form_id' => $this->form->id]);

        $this->form->delete();

        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }

    /** @test */
    public function a_form_can_build_itself()
    {
        $formView = new \Kris\LaravelFormBuilder\Form();
        FormBuilder::shouldReceive('plain')->once()->with(['name' => $this->form->title])->andReturn($formView);

        $this->assertEquals($formView, $this->form->build());
    }
}