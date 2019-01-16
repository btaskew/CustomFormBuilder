<?php

namespace Tests\Feature;

use App\Form;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_edit_form_page()
    {
        $form = $this->loginUserWithForm();

        $this->get('/forms/' . $form->id . '/edit')->assertSee($form->title);
    }

    /** @test */
    public function a_guest_cant_edit_a_form()
    {
        $this->withExceptionHandling();
        
        $form = create(Form::class);

        $this->patch('/forms/' . $form->id, ['title' => 'New title'])
            ->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_edit_their_form()
    {
        $form = $this->loginUserWithForm();

        $attributes = [
            'title' => 'New title',
            'description' => 'New description',
            'open_date' => '1990-01-01',
            'close_date' => '1990-01-02',
            'admin_email' => 'test@email.com',
            'success_text' => 'Form submitted',
            'active' => false
        ];

        $this->patch('/forms/' . $form->id, $attributes)
            ->assertStatus(200);

        $form = $form->fresh();
        $this->assertEquals('New title', $form->title);
        $this->assertEquals('New description', $form->description);
        $this->assertEquals('1990-01-01', $form->open_date);
        $this->assertEquals('1990-01-02', $form->close_date);
        $this->assertEquals('test@email.com', $form->admin_email);
        $this->assertEquals('Form submitted', $form->success_text);
        $this->assertFalse($form->active);
    }

    /** @test */
    public function a_user_can_clear_the_description_field()
    {
        $form = $this->loginUserWithForm();

        $this->patch('/forms/' . $form->id, ['title' => 'New title', 'description' => ''])
            ->assertStatus(200);

        $this->assertEquals('', $form->fresh()->description);
    }

    /** @test */
    public function a_user_cant_edit_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 999]);

        $this->login()
            ->patch('/forms/' . $form->id, ['title' => 'New title'])
            ->assertStatus(403);
    }

    /** @test */
    public function a_guest_cant_delete_a_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class);

        $this->delete('/forms/' . $form->id)->assertRedirect('login');

        $this->assertDatabaseHas('forms', ['id' => $form->id]);
    }

    /** @test */
    public function a_user_can_delete_a_form()
    {
        $form = $this->loginUserWithForm();

        $this->delete('/forms/' . $form->id)->assertStatus(200);

        $this->assertDatabaseMissing('forms', ['id' => $form->id]);
    }

    /** @test */
    public function a_user_cant_delete_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 9999]);

        $this->login()->delete('/forms/' . $form->id)->assertStatus(403);

        $this->assertDatabaseHas('forms', ['id' => $form->id]);
    }

    /** @test */
    public function a_user_can_update_the_order_of_their_forms_questions()
    {
        $form = $this->loginUserWithForm();
        $question1 = create(Question::class, ['form_id' => $form->id, 'order' => 1]);
        $question2 = create(Question::class, ['form_id' => $form->id, 'order' => 2]);

        $this->patch('/forms/' . $form->id . '/order', [
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

        $this->login()->patch('/forms/' . $form->id . '/order', [
            'order' => [
                ['question' => $question1->id, 'order' => 2],
                ['question' => $question2->id, 'order' => 1],
            ]
        ])->assertStatus(403);

        $this->assertEquals(1, $question1->fresh()->order);
        $this->assertEquals(2, $question2->fresh()->order);
    }
}