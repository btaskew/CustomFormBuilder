<?php

namespace Tests\Unit;

use App\Form;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_forms()
    {
        $user = factory(User::class)->create();
        $form = factory(Form::class)->create(['user_id' => $user->id]);

        $this->assertEquals($form->id, $user->forms->first()->id);
    }
}