<?php

namespace App\Providers;

use App\Folder;
use App\Form;
use App\Policies\FolderPolicy;
use App\Policies\FormPolicy;
use App\Policies\QuestionPolicy;
use App\Policies\SelectOptionPolicy;
use App\Policies\UserPolicy;
use App\Question;
use App\SelectOption;
use App\User;
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
        SelectOption::class => SelectOptionPolicy::class,
        User::class => UserPolicy::class,
        Folder::class => FolderPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
