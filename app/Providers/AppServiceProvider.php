<?php

namespace App\Providers;

use App\Interfaces\NewsletterSubscriberRepositoryInterface;
use App\Interfaces\SubscriberListRepositoryInterface;
use App\Interfaces\SubscriberRepositoryInterface;
use App\Interfaces\ContentUrlServiceInterface;
use App\Interfaces\GenerateOpenTrackingImageInterface;
use App\Interfaces\NewsletterContentServiceInterface;
use App\Interfaces\NewsletterDispatchInterface;
use App\Interfaces\NewsletterReportServiceInterface;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Interfaces\NewsletterUrlsRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Repositories\SubscriberEloquentRepository;
use App\Repositories\NewsletterSubscriberEloquentRepository;
use App\Repositories\NewsletterEloquentRepository;
use App\Repositories\NewsletterUrlsEloquentRepository;
use App\Repositories\SubscriberListEloquentRepository;
use App\Repositories\TagEloquentRepository;
use App\Repositories\TemplateEloquentRepository;
use App\Services\ContentUrlService;
use App\Services\GenerateOpenTrackingImageService;
use App\Services\NewsletterContentService;
use App\Services\NewsletterDispatchService;
use App\Services\NewsletterReportService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Interfaces\ConfigRepositoryInterface;
use App\Repositories\ConfigEloquentRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SubscriberRepositoryInterface::class, SubscriberEloquentRepository::class);
        $this->app->bind(SubscriberListRepositoryInterface::class, SubscriberListEloquentRepository::class);
        $this->app->bind(NewsletterSubscriberRepositoryInterface::class, NewsletterSubscriberEloquentRepository::class);
        $this->app->bind(NewsletterReportServiceInterface::class, NewsletterReportService::class);
        $this->app->bind(NewsletterContentServiceInterface::class, NewsletterContentService::class);
        $this->app->bind(ContentUrlServiceInterface::class, ContentUrlService::class);
        $this->app->bind(GenerateOpenTrackingImageInterface::class, GenerateOpenTrackingImageService::class);
        $this->app->bind(NewsletterDispatchInterface::class, NewsletterDispatchService::class);
        $this->app->bind(NewsletterUrlsRepositoryInterface::class, NewsletterUrlsEloquentRepository::class);
        $this->app->bind(NewsletterRepositoryInterface::class, NewsletterEloquentRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagEloquentRepository::class);
        $this->app->bind(TemplateRepositoryInterface::class, TemplateEloquentRepository::class);
        $this->app->bind(ConfigRepositoryInterface::class, ConfigEloquentRepository::class);
    }
}
