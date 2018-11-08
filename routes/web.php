<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Auth
Auth::routes();

// App
Route::middleware(['auth'])->group(function ()
{
    Route::get('/logout', ['as' => 'dashboard', 'uses' => 'Auth\LoginController@logout']);

    Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

    // Automations
    Route::resource('automations', 'AutomationsController');
    Route::resource('automations.steps', 'AutomationStepsController');
    Route::resource('automations.steps.email', 'AutomationStepEmailController');

    Route::get('automations/{automation}/steps/{automationStep}/email/content/edit', 'AutomationStepEmailContentController@edit')
        ->name('automations.steps.email.content.edit');

    Route::resource('automations.emails', 'AutomationEmailsController')->except([
        'index',
        'show',
    ]);
    Route::get('automations/{automation}/emails/{email}/content', ['as' => 'automations.emails.content.edit', 'uses' => 'AutomationEmailContentController@edit']);
    Route::get('automations/{$id}/confirm', ['as' => 'automations.confirm', 'uses' => 'AutomationsController@confirm']);

    // Subscribers
    Route::get('subscribers/export', ['as' => 'subscribers.export', 'uses' => 'SubscribersController@export']);
    Route::get('subscribers/import', ['as' => 'subscribers.import', 'uses' => 'SubscribersImportController@show']);
    Route::post('subscribers/import', ['as' => 'subscribers.import.store', 'uses' => 'SubscribersImportController@store']);
    Route::resource('subscribers', 'SubscribersController');

    // Segments
    Route::resource('segments', 'SegmentsController');

    // Campaigns
    Route::resource('campaigns', 'CampaignsController');
    Route::resource('campaigns.emails', 'CampaignEmailsController')->except([
        'index',
        'show',
    ]);
    Route::get('campaigns/{campaign}/email/content', 'CampaignEmailContentController@edit')
        ->name('campaigns.emails.content.edit');
    Route::put('campaigns/{campaign}/email/content', 'CampaignEmailContentController@update')
        ->name('campaigns.emails.content.update');

    Route::put('campaigns/{id}/send', ['as' => 'campaigns.send', 'uses' => 'CampaignsController@send']);
    Route::get('campaigns/{id}/status', ['as' => 'campaigns.status', 'uses' => 'CampaignsController@status']);

    Route::get('campaigns/{id}/report', ['as' => 'campaigns.report', 'uses' => 'CampaignReportsController@report']);
    Route::get('campaigns/{id}/recipients', ['as' => 'campaigns.recipients', 'uses' => 'CampaignReportsController@recipients']);

    // Templates
    Route::resource('templates', 'TemplatesController');

    Route::get('tracker/opens/{campaignId}/{contactId}', ['as' => 'tracker.opens', 'uses' => 'TrackerController@opens']);
    Route::get('tracker/clicks/{campaignId}/{contactId}/{linkId}', ['as' => 'tracker.clicks', 'uses' => 'TrackerController@clicks']);

    Route::get('unsubscribe/{subscriberId}', ['as' => 'subscriptions.unsubscribe', 'uses' => 'SubscriptionsController@unsubscribe']);
    Route::post('subscriptions', ['as' => 'subscriptions.update', 'uses' => 'SubscriptionsController@update']);
    Route::get('subscribe/{subscriberId}', ['as' => 'subscriptions.subscribe', 'uses' => 'SubscriptionsController@subscribe']);

    Route::get('providers', ['as' => 'providers.index', 'uses' => 'ProvidersController@index']);
    Route::get('providers/create', ['as' => 'providers.create', 'uses' => 'ProvidersController@create']);
    Route::get('providers/type/{id}', ['as' => 'providers.ajax', 'uses' => 'ProvidersController@providersTypeAjax']);
    Route::post('providers', ['as' => 'providers.store', 'uses' => 'ProvidersController@store']);
    Route::get('providers/{id}/edit', ['as' => 'providers.edit', 'uses' => 'ProvidersController@edit']);
    Route::post('providers/{id}', ['as' => 'providers.update', 'uses' => 'ProvidersController@update']);
});
