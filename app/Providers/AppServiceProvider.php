<?php

namespace App\Providers;

use App\Interfaces\AutomationRepositoryInterface;
use App\Interfaces\CampaignContentServiceInterface;
use App\Interfaces\CampaignDispatchInterface;
use App\Interfaces\CampaignReportServiceInterface;
use App\Interfaces\CampaignRepositoryInterface;
use App\Interfaces\CampaignSubscriberRepositoryInterface;
use App\Interfaces\CampaignUrlsRepositoryInterface;
use App\Interfaces\EmailRepositoryInterface;
use App\Interfaces\EmailWebhookServiceInterface;
use App\Interfaces\ProviderRepositoryInterface;
use App\Interfaces\SegmentRepositoryInterface;
use App\Interfaces\SubscriberRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Repositories\AutomationEloquentRepository;
use App\Repositories\CampaignSubscriberEloquentRepository;
use App\Repositories\CampaignEloquentRepository;
use App\Repositories\CampaignUrlsEloquentRepository;
use App\Repositories\EmailEloquentRepository;
use App\Repositories\ProviderEloquentRepository;
use App\Repositories\SegmentEloquentRepository;
use App\Repositories\SubscriberEloquentRepository;
use App\Repositories\TagEloquentRepository;
use App\Repositories\TemplateEloquentRepository;
use App\Services\EmailWebhookService;
use App\Services\CampaignContentService;
use App\Services\CampaignDispatchService;
use App\Services\CampaignReportService;

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

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SubscriberRepositoryInterface::class, SubscriberEloquentRepository::class);
        $this->app->bind(SegmentRepositoryInterface::class, SegmentEloquentRepository::class);
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
        $this->app->bind(ProviderRepositoryInterface::class, ProviderEloquentRepository::class);
        $this->app->bind(AutomationRepositoryInterface::class, AutomationEloquentRepository::class);
        $this->app->bind(EmailRepositoryInterface::class, EmailEloquentRepository::class);
        $this->app->bind(EmailWebhookServiceInterface::class, EmailWebhookService::class);
    }
}
