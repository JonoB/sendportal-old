<?php

namespace App\Providers;

use App\Interfaces\CampaignContentServiceInterface;
use App\Interfaces\DeliveryDispatchInterface;
use App\Interfaces\CampaignReportServiceInterface;
use App\Interfaces\CampaignRepositoryInterface;
use App\Interfaces\CampaignSubscriberTenantRepository;
use App\Interfaces\EmailWebhookServiceInterface;
use App\Repositories\CampaignSubscriberEloquentRepository;
use App\Repositories\CampaignTenantRepository;
use App\Services\Content\MergeContent;
use App\Services\Messages\DispatchMessage;
use App\Services\EmailWebhookService;
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
        $this->app->bind(CampaignSubscriberTenantRepository::class, CampaignSubscriberEloquentRepository::class);
        $this->app->bind(CampaignReportServiceInterface::class, CampaignReportService::class);
        $this->app->bind(CampaignContentServiceInterface::class, MergeContent::class);
        $this->app->bind(DeliveryDispatchInterface::class, DispatchMessage::class);
        $this->app->bind(CampaignRepositoryInterface::class, CampaignTenantRepository::class);
        $this->app->bind(EmailWebhookServiceInterface::class, EmailWebhookService::class);
    }
}
