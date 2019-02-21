<?php

namespace Tests\Feature\Responses;

use App\Form;
use App\FormResponse;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewFormResponseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_view_a_forms_responses()
    {
        $this->get('/forms/1/responses')->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_view_their_forms_responses()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);
        create(FormResponse::class, [
            'form_id' => $form->id,
            'response' => '{"' . $question->id . '":"My response"}'
        ]);

        $this->get(formPath($form) . '/responses')
            ->assertStatus(200)
            ->assertSee('My response');
    }

    /** @test */
    public function a_user_cant_view_another_users_forms_responses()
    {
        $form = create(Form::class, ['user_id' => 999]);
        $question = create(Question::class, ['form_id' => $form->id]);
        create(FormResponse::class, [
            'form_id' => $form->id,
            'response' => '{"' . $question->id . '":"My response"}'
        ]);

        $this->login()
            ->get(formPath($form) . '/responses')
            ->assertStatus(403)
            ->assertDontSee('My response');
    }

    /** @test */
    public function a_user_can_view_another_forms_responses_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');
        $question = create(Question::class, ['form_id' => $form->id]);
        create(FormResponse::class, [
            'form_id' => $form->id,
            'response' => '{"' . $question->id . '":"My response"}'
        ]);

        $this->get(formPath($form) . '/responses')
            ->assertStatus(200)
            ->assertSee("My response");
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