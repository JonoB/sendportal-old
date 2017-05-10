<?php

namespace App\Providers;

use App\Interfaces\ContactRepositoryInterface;
use App\Interfaces\ContentUrlServiceInterface;
use App\Interfaces\GenerateOpenTrackingImageInterface;
use App\Interfaces\NewsletterContentServiceInterface;
use App\Interfaces\NewsletterDispatchInterface;
use App\Interfaces\NewsletterUrlsRepositoryInterface;
use App\Interfaces\ContactNewsletterRepositoryInterface;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Interfaces\SegmentRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Repositories\ContactEloquentRepository;
use App\Repositories\NewsletterEloquentRepository;
use App\Repositories\NewsletterUrlsEloquentRepository;
use App\Repositories\ContactNewsletterEloquentRepository;
use App\Repositories\SegmentEloquentRepository;
use App\Repositories\TemplateEloquentRepository;
use App\Services\ContentUrlService;
use App\Services\GenerateOpenTrackingImageService;
use App\Services\NewsletterContentService;
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
        $this->app->bind(ContactNewsletterRepositoryInterface::class, ContactNewsletterEloquentRepository::class);
        $this->app->bind(NewsletterContentServiceInterface::class, NewsletterContentService::class);
        $this->app->bind(ContentUrlServiceInterface::class, ContentUrlService::class);
        $this->app->bind(GenerateOpenTrackingImageInterface::class, GenerateOpenTrackingImageService::class);
        $this->app->bind(NewsletterDispatchInterface::class, NewsletterDispatchService::class);
        $this->app->bind(NewsletterUrlsRepositoryInterface::class, NewsletterUrlsEloquentRepository::class);
        $this->app->bind(NewsletterRepositoryInterface::class, NewsletterEloquentRepository::class);
        $this->app->bind(SegmentRepositoryInterface::class, SegmentEloquentRepository::class);
        $this->app->bind(TemplateRepositoryInterface::class, TemplateEloquentRepository::class);
    }
}
