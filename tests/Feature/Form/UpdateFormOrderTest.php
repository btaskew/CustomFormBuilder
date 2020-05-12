<?php

namespace Tests\Feature\Form;

use App\Form;
use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateFormOrderTest extends TestCase
{
    use RefreshDatabase;

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
    public function a_guest_cant_update_the_order_of_a_forms_questions()
    {
        $form = create(Form::class);
        $question1 = create(Question::class, ['form_id' => $form->id, 'order' => 1]);
        $question2 = create(Question::class, ['form_id' => $form->id, 'order' => 2]);

        $this->patch(formPath($form) . '/order', [
            'order' => [
                ['question' => $question1->id, 'order' => 2],
                ['question' => $question2->id, 'order' => 1],
            ]
        ])->assertRedirect('login');

        $this->assertEquals(1, $question1->fresh()->order);
        $this->assertEquals(2, $question2->fresh()->order);
    }

    /** @test */
    public function a_user_cant_update_the_order_of_another_users_form()
    {
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
    public function a_user_can_update_the_order_of_a_forms_questions_they_have_update_access_to()
    {
        $form = $this->createFormWithAccess('update');
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
    public function a_user_cant_update_the_order_of_a_forms_questions_they_have_view_access_to()
    {
        $form = $this->createFormWithAccess('view');
        $question1 = create(Question::class, ['form_id' => $form->id, 'order' => 1]);
        $question2 = create(Question::class, ['form_id' => $form->id, 'order' => 2]);

        $this->patch(formPath($form) . '/order', [
            'order' => [
                ['question' => $question1->id, 'order' => 2],
                ['question' => $question2->id, 'order' => 1],
            ]
        ])->assertStatus(403);

        $this->assertEquals(1, $question1->fresh()->order);
        $this->assertEquals(2, $question2->fresh()->order);
    }
}
