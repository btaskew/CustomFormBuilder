<?php

namespace Tests;

use App\Form;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();
        // This is temporary as it makes TDD much easier
        $this->withoutExceptionHandling();
    }

    public function login()
    {
        $this->be(create(User::class));

        return $this;
    }

    /**
     * @return \App\Form
     */
    public function loginUserWithForm()
    {
        $this->login();
        return create(Form::class, ['user_id' => auth()->user()->id]);
    }
}
