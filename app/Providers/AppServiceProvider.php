<?php

namespace App\Providers;

use App\Interfaces\ContactRepositoryInterface;
use App\Interfaces\GenerateOpenTrackingImageInterface;
use App\Interfaces\NewsletterDispatchInterface;
use App\Interfaces\NewsletterOpenRepositoryInterface;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Interfaces\SegmentRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Repositories\ContactEloquentRepository;
use App\Repositories\NewsletterEloquentRepository;
use App\Repositories\NewsletterOpenEloquentRepository;
use App\Repositories\SegmentEloquentRepository;
use App\Repositories\TemplateEloquentRepository;
use App\Services\GenerateOpenTrackingImageService;
use App\Services\NewsletterDispatchService;
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
        $this->app->bind(GenerateOpenTrackingImageInterface::class, GenerateOpenTrackingImageService::class);
        $this->app->bind(SegmentRepositoryInterface::class, SegmentEloquentRepository::class);
        $this->app->bind(NewsletterRepositoryInterface::class, NewsletterEloquentRepository::class);
        $this->app->bind(NewsletterDispatchInterface::class, NewsletterDispatchService::class);
        $this->app->bind(NewsletterOpenRepositoryInterface::class, NewsletterOpenEloquentRepository::class);
        $this->app->bind(TemplateRepositoryInterface::class, TemplateEloquentRepository::class);
    }
}
