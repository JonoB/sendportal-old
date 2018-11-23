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

    Route::get('campaigns/{id}/status', ['as' => 'campaigns.status', 'uses' => 'CampaignsController@status']);

    Route::get('campaigns/{id}/report', ['as' => 'campaigns.report', 'uses' => 'CampaignReportsController@show']);

    Route::get('campaigns/{id}/confirm', 'CampaignsController@confirm')
        ->name('campaigns.confirm');
    Route::put('campaigns/{id}/send', 'CampaignsController@send')
        ->name('campaigns.send');

    // Templates
    Route::resource('templates', 'TemplatesController')
        ->except(['show']);

    Route::get('unsubscribe/{subscriberHash}', 'SubscriptionsController@unsubscribe')->name('subscriptions.unsubscribe');
    Route::get('subscribe/{subscriberHash}', 'SubscriptionsController@subscribe')->name('subscriptions.subscribe');
    Route::put('subscriptions/{subscriberId}', 'SubscriptionsController@update')->name('subscriptions.update');

    Route::get('providers', ['as' => 'providers.index', 'uses' => 'ProvidersController@index']);
    Route::get('providers/create', ['as' => 'providers.create', 'uses' => 'ProvidersController@create']);
    Route::get('providers/type/{id}', ['as' => 'providers.ajax', 'uses' => 'ProvidersController@providersTypeAjax']);
    Route::post('providers', ['as' => 'providers.store', 'uses' => 'ProvidersController@store']);
    Route::get('providers/{id}/edit', ['as' => 'providers.edit', 'uses' => 'ProvidersController@edit']);
    Route::post('providers/{id}', ['as' => 'providers.update', 'uses' => 'ProvidersController@update']);
});
