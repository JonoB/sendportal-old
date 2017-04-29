<?php

namespace App\Providers;

use App\Interfaces\ContactRepositoryInterface;
use App\Repositories\ContactEloquentRepository;
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
    }
}
