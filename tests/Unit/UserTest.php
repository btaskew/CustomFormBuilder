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
        $user = create(User::class);
        $form = create(Form::class, ['user_id' => $user->id]);

        $this->assertTrue($user->forms->first()->is($form));
    }
}