<?php

namespace App\Providers;

use App\Interfaces\ContactRepositoryInterface;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Repositories\ContactEloquentRepository;
use App\Repositories\NewsletterEloquentRepository;
use App\Repositories\TemplateEloquentRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ContactRepositoryInterface::class, ContactEloquentRepository::class);
        $this->app->bind(NewsletterRepositoryInterface::class, NewsletterEloquentRepository::class);
        $this->app->bind(TemplateRepositoryInterface::class, TemplateEloquentRepository::class);
    }
}
