<?php

namespace Tests\Unit;

use App\Form;
use App\Question;
use App\Specifications\CanSetResponseEmailField;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanSetResponseEmailFieldTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \App\Form
     */
    private $form;

    protected function setUp(): void
    {
        parent::setUp();
        $this->form = create(Form::class);
    }

    /** @test */
    public function returns_true_if_question_valid()
    {
        $question = create(Question::class, ['form_id' => $this->form->id, 'type' => 'email']);

        $this->assertTrue(CanSetResponseEmailField::isSatisfiedBy($question->id, $this->form));
    }

    /** @test */
    public function returns_true_if_null_question_passed()
    {
        $this->assertTrue(CanSetResponseEmailField::isSatisfiedBy(null, $this->form));
    }

    /** @test */
    public function returns_false_if_question_does_not_exist()
    {
        $this->assertFalse(CanSetResponseEmailField::isSatisfiedBy(999, $this->form));
    }

    /** @test */
    public function returns_false_if_question_exists_on_different_form()
    {
        $question = create(Question::class, ['form_id' => 999]);

        $this->assertFalse(CanSetResponseEmailField::isSatisfiedBy($question->id, $this->form));
    }

    /** @test */
    public function returns_false_if_question_is_not_email_type()
    {
        $question = create(Question::class, ['form_id' => $this->form->id, 'type' => 'text']);

        $this->assertFalse(CanSetResponseEmailField::isSatisfiedBy($question->id, $this->form));
    }
}
