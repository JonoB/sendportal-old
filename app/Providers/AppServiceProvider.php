<?php

namespace App\Providers;

use App\Interfaces\CampaignSubscriberRepositoryInterface;
use App\Interfaces\SubscriberListRepositoryInterface;
use App\Interfaces\SubscriberRepositoryInterface;
use App\Interfaces\ContentUrlServiceInterface;
use App\Interfaces\GenerateOpenTrackingImageInterface;
use App\Interfaces\CampaignContentServiceInterface;
use App\Interfaces\CampaignDispatchInterface;
use App\Interfaces\CampaignReportServiceInterface;
use App\Interfaces\CampaignRepositoryInterface;
use App\Interfaces\CampaignUrlsRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Repositories\SubscriberEloquentRepository;
use App\Repositories\CampaignSubscriberEloquentRepository;
use App\Repositories\CampaignEloquentRepository;
use App\Repositories\CampaignUrlsEloquentRepository;
use App\Repositories\SubscriberListEloquentRepository;
use App\Repositories\TagEloquentRepository;
use App\Repositories\TemplateEloquentRepository;
use App\Services\ContentUrlService;
use App\Services\GenerateOpenTrackingImageService;
use App\Services\CampaignContentService;
use App\Services\CampaignDispatchService;
use App\Services\CampaignReportService;
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
        $this->app->bind(CampaignSubscriberRepositoryInterface::class, CampaignSubscriberEloquentRepository::class);
        $this->app->bind(CampaignReportServiceInterface::class, CampaignReportService::class);
        $this->app->bind(CampaignContentServiceInterface::class, CampaignContentService::class);
        $this->app->bind(ContentUrlServiceInterface::class, ContentUrlService::class);
        $this->app->bind(GenerateOpenTrackingImageInterface::class, GenerateOpenTrackingImageService::class);
        $this->app->bind(CampaignDispatchInterface::class, CampaignDispatchService::class);
        $this->app->bind(CampaignUrlsRepositoryInterface::class, CampaignUrlsEloquentRepository::class);
        $this->app->bind(CampaignRepositoryInterface::class, CampaignEloquentRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagEloquentRepository::class);
        $this->app->bind(TemplateRepositoryInterface::class, TemplateEloquentRepository::class);
        $this->app->bind(ConfigRepositoryInterface::class, ConfigEloquentRepository::class);
    }
}
