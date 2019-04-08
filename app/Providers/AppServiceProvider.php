<?php

namespace App\Providers;

use App\Contracts\QuestionBankReplicator;
use App\Contracts\QuestionSetter;
use App\Contracts\ResponseFormatter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            QuestionBankReplicator::class,
            \App\Services\QuestionBankReplicator::class
        );

        $this->app->singleton(
            QuestionSetter::class,
            \App\Services\QuestionSetter::class
        );

        $this->app->singleton(
            ResponseFormatter::class,
            \App\Services\ResponseFormatter::class
        );
    }

    /**
     * @codeCoverageIgnore
     * @return array
     */
    public function provides()
    {
        return [
            QuestionBankReplicator::class,
            QuestionSetter::class,
            ResponseFormatter::class,
        ];
    }
}
