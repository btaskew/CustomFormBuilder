<?php

namespace Tests\Feature;

use App\Form;
use App\FormResponse;
use App\FormUser;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewFormResponseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_their_forms_responses()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);
        create(FormResponse::class, [
            'form_id' => $form->id,
            'response' => '{"' . $question->id . '":"value"}'
        ]);

        $this->get(formPath($form) . '/responses')
            ->assertStatus(200)
            ->assertSee("value");
    }

    /** @test */
    public function a_user_cant_view_another_users_forms_responses()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 999]);
        $question = create(Question::class, ['form_id' => $form->id]);
        create(FormResponse::class, [
            'form_id' => $form->id,
            'response' => '{"' . $question->id . '":"value"}'
        ]);

        $this->login()
            ->get(formPath($form) . '/responses')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_view_another_forms_responses_they_have_view_access_to()
    {
        $this->login();
        $form = create(Form::class, ['user_id' => 999]);
        $question = create(Question::class, ['form_id' => $form->id]);
        create(FormResponse::class, [
            'form_id' => $form->id,
            'response' => '{"' . $question->id . '":"value"}'
        ]);
        create(FormUser::class, [
            'user_id' => auth()->user()->id,
            'form_id' => $form->id,
            'access' => 'view'
        ]);

        $this->get(formPath($form) . '/responses')
            ->assertStatus(200)
            ->assertSee("value");
    }

    /** @test */
    public function viewing_form_responses_doesnt_show_label_fields()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);
        $labelQuestion = create(Question::class, ['form_id' => $form->id, 'type' => 'label']);

        create(FormResponse::class, [
            'form_id' => $form->id,
            'response' => '{"' . $question->id . '":"value"}'
        ]);

        $this->get(formPath($form) . '/responses')
            ->assertStatus(200)
            ->assertDontSee($labelQuestion->title);
    }
}