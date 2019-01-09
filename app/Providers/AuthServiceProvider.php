<?php

namespace App\Providers;

use App\Form;
use App\Policies\FormPolicy;
use App\Policies\QuestionPolicy;
use App\Policies\SelectOptionPolicy;
use App\Question;
use App\SelectOption;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Form::class => FormPolicy::class,
        Question::class => QuestionPolicy::class,
        SelectOption::class => SelectOptionPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
