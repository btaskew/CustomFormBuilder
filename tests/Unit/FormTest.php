<?php

namespace Tests\Unit;

use App\Form;
use App\FormResponse;
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

        $this->assertEquals($question->id, $this->form->questions->first()->id);
    }

    /** @test */
    public function a_form_has_responses()
    {
        $response = create(FormResponse::class, ['form_id' => $this->form->id]);

        $this->assertEquals($response->id, $this->form->responses->first()->id);
    }

    /** @test */
    public function a_forms_questions_are_deleted_when_the_form_is_deleted()
    {
        $question = create(Question::class, ['form_id' => $this->form->id]);

        $this->form->delete();

        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }

    /** @test */
    public function a_form_is_inactive_if_current_date_before_open_date()
    {
        $form = create(Form::class, ['open_date' => '01-01-2990', 'close_date' => '02-01-2990', 'active' => true]);

        $this->assertFalse($form->isActive());
    }

    /** @test */
    public function a_form_is_inactive_if_current_date_after_close_date()
    {
        $form = create(Form::class, ['open_date' => '01-01-1990', 'close_date' => '02-01-1990', 'active' => true]);

        $this->assertFalse($form->isActive());
    }

    /** @test */
    public function a_form_is_active_if_current_date_between_open_and_close_dates()
    {
        $form = create(Form::class, ['open_date' => '01-01-1990', 'close_date' => '01-01-2990', 'active' => true]);

        $this->assertTrue($form->isActive());
    }

    /** @test */
    public function a_form_is_inactive_if_active_is_false()
    {
        //Even if dates are within range
        $form = create(Form::class, ['open_date' => '01-01-1990', 'close_date' => '01-01-2990', 'active' => false]);

        $this->assertFalse($form->isActive());
    }

    /** @test */
    public function a_form_can_get_its_questions_in_order()
    {
        $question2 = create(Question::class, ['order' => 2, 'form_id' => $this->form->id]);
        $question1 = create(Question::class, ['order' => 1, 'form_id' => $this->form->id]);

        $this->assertEquals($question1->id, $this->form->getOrderedQuestions()->first()->id);
        $this->assertEquals($question2->id, $this->form->getOrderedQuestions()[1]->id);
    }

    /** @test */
    public function a_forms_description_is_sanitized_when_fetched()
    {
        $form = create(Form::class, ['description' => '<script>alert("Bad code")</script><h1>Good code</h1>']);

        $this->assertEquals('<h1>Good code</h1>', $form->description);
    }
}