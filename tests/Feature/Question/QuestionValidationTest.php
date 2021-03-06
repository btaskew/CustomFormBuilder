<?php

namespace Tests\Feature\Question;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var string
     */
    private $uri;

    protected function setUp(): void
    {
        parent::setUp();

        $form = $this->loginUserWithForm();
        $this->uri = formPath($form) . '/questions';
    }

    /** @test */
    public function a_question_title_is_required()
    {
        $this->postQuestion(['title' => null])
            ->assertStatus(422)
            ->assertSee('title');
    }

    /** @test */
    public function a_question_title_cannot_be_too_long()
    {
        $this->postQuestion(['title' => str_repeat('t', 260)])
            ->assertStatus(422)
            ->assertSee('title');
    }

    /** @test */
    public function a_type_is_required()
    {
        $this->postQuestion(['type' => null])
            ->assertStatus(422)
            ->assertSee('type');
    }

    /** @test */
    public function a_valid_type_is_required()
    {
        $this->postQuestion(['type' => 'foobar'])
            ->assertStatus(422)
            ->assertSee('type');
    }

    /** @test */
    public function a_value_is_required_for_select_questions()
    {
        $this->postQuestion(['type' => 'checkbox', 'options' => ['display_value' => 'Display']])
            ->assertStatus(422)
            ->assertSee('value');
    }

    /** @test */
    public function a_display_value_is_required_for_select_questions()
    {
        $this->postQuestion(['type' => 'checkbox', 'options' => ['value' => 'value']])
            ->assertStatus(422)
            ->assertSee('display_value');
    }

    /** @test */
    public function at_least_one_option_is_required_for_select_questions()
    {
        $this->postQuestion(['type' => 'checkbox', 'options' => []])
            ->assertStatus(422)
            ->assertSee('options');

        $this->postQuestion(['type' => 'radio', 'options' => []])
            ->assertStatus(422)
            ->assertSee('options');

        $this->postQuestion(['type' => 'dropdown', 'options' => []])
            ->assertStatus(422)
            ->assertSee('options');
    }


    /** @test */
    public function a_user_cant_add_multiple_options_with_the_same_value()
    {
        $this->postQuestion(['type' => 'checkbox', 'options' => [
            ['id' => null, 'value' => 'value', 'display_value' => 'New value'],
            ['id' => null, 'value' => 'value', 'display_value' => 'Other new value'],
        ]])
            ->assertStatus(422)
            ->assertSee('options');
    }

    /** @test */
    public function a_question_id_and_value_are_required_when_setting_a_visibility_requirement()
    {
        $this->postQuestion(['visibility_requirement' => true])
            ->assertStatus(422)
            ->assertSee('required_question')
            ->assertSee('required_value');
    }

    /**
     * @param array $attributes
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function postQuestion(array $attributes = [])
    {
        $defaultAttributes = [
            'title' => 'Title',
            'type' => 'text'
        ];

        return $this->json('post', $this->uri, array_merge($defaultAttributes, $attributes));
    }
}