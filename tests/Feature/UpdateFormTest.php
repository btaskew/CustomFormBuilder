<?php

namespace Tests\Feature;

use App\Form;
use App\FormUser;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_the_edit_form_page_for_their_form()
    {
        $form = $this->loginUserWithForm();

        $this->get(formPath($form) . '/edit')->assertSee($form->title);
    }

    /** @test */
    public function a_user_cant_view_the_edit_form_page_for_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 999]);

        $this->login()->get(formPath($form) . '/edit')->assertStatus(403);
    }

    /** @test */
    public function a_guest_cant_edit_a_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class);

        $this->patch(formPath($form), ['title' => 'New title'])
            ->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_edit_their_form()
    {
        $form = $this->loginUserWithForm();
        $question = create(Question::class, ['form_id' => $form->id]);

        $attributes = [
            'title' => 'New title',
            'description' => 'New description',
            'open_date' => '1990-01-01',
            'close_date' => '1990-01-02',
            'admin_email' => 'test@email.com',
            'success_text' => 'Form submitted',
            'response_email' => 'Response text',
            'response_email_field' => $question->id,
            'active' => false
        ];

        $this->patch(formPath($form), $attributes)
            ->assertStatus(200);

        $form = $form->fresh();
        $this->assertEquals('New title', $form->title);
        $this->assertEquals('New description', $form->description);
        $this->assertEquals('1990-01-01', $form->open_date);
        $this->assertEquals('1990-01-02', $form->close_date);
        $this->assertEquals('test@email.com', $form->admin_email);
        $this->assertEquals('Form submitted', $form->success_text);
        $this->assertEquals('Response text', $form->response_email);
        $this->assertEquals($question->id, $form->response_email_field);
        $this->assertFalse($form->active);
    }

    /** @test */
    public function a_user_cant_edit_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 999]);

        $this->login()
            ->patch(formPath($form), ['title' => 'New title'])
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_edit_another_users_form_that_they_have_edit_access_to()
    {
        $this->login();
        $form = create(Form::class, ['user_id' => 999]);
        create(FormUser::class, [
            'user_id' => auth()->user()->id,
            'form_id' => $form->id,
            'access' => 'edit'
        ]);

        $attributes = [
            'title' => 'New title',
            'open_date' => '1990-01-01',
            'close_date' => '1990-01-02',
            'active' => false
        ];

        $this->patch(formPath($form), $attributes)
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_cant_edit_another_users_form_that_they_have_view_access_to()
    {
        $this->withExceptionHandling();

        $this->login();
        $form = create(Form::class, ['user_id' => 999]);
        create(FormUser::class, [
            'user_id' => auth()->user()->id,
            'form_id' => $form->id,
            'access' => 'view'
        ]);

        $attributes = [
            'title' => 'New title',
            'open_date' => '1990-01-01',
            'close_date' => '1990-01-02',
            'active' => false
        ];

        $this->patch(formPath($form), $attributes)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_update_the_order_of_their_forms_questions()
    {
        $form = $this->loginUserWithForm();
        $question1 = create(Question::class, ['form_id' => $form->id, 'order' => 1]);
        $question2 = create(Question::class, ['form_id' => $form->id, 'order' => 2]);

        $this->patch(formPath($form) . '/order', [
            'order' => [
                ['question' => $question1->id, 'order' => 2],
                ['question' => $question2->id, 'order' => 1],
            ]
        ])->assertStatus(200);

        $this->assertEquals(2, $question1->fresh()->order);
        $this->assertEquals(1, $question2->fresh()->order);
    }

    /** @test */
    public function a_user_cant_update_the_order_of_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 9999]);
        $question1 = create(Question::class, ['form_id' => $form->id, 'order' => 1]);
        $question2 = create(Question::class, ['form_id' => $form->id, 'order' => 2]);

        $this->login()->patch(formPath($form) . '/order', [
            'order' => [
                ['question' => $question1->id, 'order' => 2],
                ['question' => $question2->id, 'order' => 1],
            ]
        ])->assertStatus(403);

        $this->assertEquals(1, $question1->fresh()->order);
        $this->assertEquals(2, $question2->fresh()->order);
    }

    /** @test */
    public function a_user_can_update_the_order_of_a_forms_questions_they_have_access_to()
    {
        $this->login();
        $form = create(Form::class, ['user_id' => 999]);
        create(FormUser::class, [
            'user_id' => auth()->user()->id,
            'form_id' => $form->id,
            'access' => 'edit'
        ]);
        $question1 = create(Question::class, ['form_id' => $form->id, 'order' => 1]);
        $question2 = create(Question::class, ['form_id' => $form->id, 'order' => 2]);

        $this->patch(formPath($form) . '/order', [
            'order' => [
                ['question' => $question1->id, 'order' => 2],
                ['question' => $question2->id, 'order' => 1],
            ]
        ])->assertStatus(200);

        $this->assertEquals(2, $question1->fresh()->order);
        $this->assertEquals(1, $question2->fresh()->order);
    }
}