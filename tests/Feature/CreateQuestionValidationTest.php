<?php

namespace Tests\Feature;

use App\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateQuestionValidationTest extends TestCase
{
    use RefreshDatabase;

    private $uri;

    protected function setUp()
    {
        parent::setUp();
        $this->withExceptionHandling();

        $this->login();
        $form = create(Form::class, ['user_id' => auth()->user()->id]);
        $this->uri = '/forms/' . $form->id . '/questions';
    }

    /** @test */
    public function a_title_is_required()
    {
        $this->json('post', $this->uri, ['type' => 'text'])
            ->assertStatus(422)
            ->assertSee('title');
    }

    /** @test */
    public function a_type_is_required()
    {
        $this->json('post', $this->uri, ['title' => 'Title'])
            ->assertStatus(422)
            ->assertSee('type');
    }

    /** @test */
    public function a_valid_type_is_required()
    {
        $this->json('post', $this->uri, ['title' => 'Title', 'type' => 'foobar'])
            ->assertStatus(422)
            ->assertSee('type');
    }

    /** @test */
    public function a_value_is_required_for_select_questions()
    {
        $this->json('post', $this->uri, [
            'title' => 'Title', 'type' => 'foobar', 'options' => ['display_value' => 'Display']
        ])
            ->assertStatus(422)
            ->assertSee('value');
    }

    /** @test */
    public function a_display_value_is_required_for_select_questions()
    {
        $this->json('post', $this->uri, [
            'title' => 'Title', 'type' => 'foobar', 'options' => ['value' => 'value']
        ])
            ->assertStatus(422)
            ->assertSee('display_value');
    }
}