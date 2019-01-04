<?php

namespace Tests\Feature;

use App\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_all_their_forms()
    {
        $this->login();
        $form = factory(Form::class)->create(['user_id' => auth()->user()->id]);

        $this->json('get', '/forms')
            ->assertStatus(200)
            ->assertSee($form->title);
    }
}