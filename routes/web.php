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

    Route::resource('automation', 'AutomationsController');

    Route::get('subscribers/export', ['as' => 'subscribers.export', 'uses' => 'SubscribersController@export']);
    Route::get('subscribers/import', ['as' => 'subscribers.import', 'uses' => 'ImportSubscribersController@show']);
    Route::post('subscribers/import', ['as' => 'subscribers.import.store', 'uses' => 'ImportSubscribersController@store']);
    Route::resource('subscribers', 'SubscribersController');

    Route::resource('segments', 'SegmentsController');

    Route::resource('campaigns', 'CampaignsController');
    Route::get('campaigns/{id}/template', ['as' => 'campaigns.template', 'uses' => 'CampaignsController@template']);
    Route::put('campaigns/{id}/template', ['as' => 'campaigns.template.update', 'uses' => 'CampaignsController@updateTemplate']);
    Route::get('campaigns/{id}/design', ['as' => 'campaigns.design', 'uses' => 'CampaignsController@design']);
    Route::put('campaigns/{id}/design', ['as' => 'campaigns.design.update', 'uses' => 'CampaignsController@updateDesign']);
    Route::get('campaigns/{id}/confirm', ['as' => 'campaigns.confirm', 'uses' => 'CampaignsController@confirm']);
    Route::put('campaigns/{id}/send', ['as' => 'campaigns.send', 'uses' => 'CampaignsController@send']);
    Route::get('campaigns/{id}/status', ['as' => 'campaigns.status', 'uses' => 'CampaignsController@status']);

    Route::get('campaigns/{id}/report', ['as' => 'campaigns.report', 'uses' => 'CampaignReportsController@report']);
    Route::get('campaigns/{id}/recipients', ['as' => 'campaigns.recipients', 'uses' => 'CampaignReportsController@recipients']);

    Route::resource('templates', 'TemplatesController');

    Route::get('tracker/opens/{campaignId}/{contactId}', ['as' => 'tracker.opens', 'uses' => 'TrackerController@opens']);
    Route::get('tracker/clicks/{campaignId}/{contactId}/{linkId}', ['as' => 'tracker.clicks', 'uses' => 'TrackerController@clicks']);

    Route::get('unsubscribe/{subscriberId}', ['as' => 'subscriptions.unsubscribe', 'uses' => 'SubscriptionsController@unsubscribe']);
    Route::post('subscriptions', ['as' => 'subscriptions.update', 'uses' => 'SubscriptionsController@update']);
    Route::get('subscribe/{subscriberId}', ['as' => 'subscriptions.subscribe', 'uses' => 'SubscriptionsController@subscribe']);

    Route::get('config', ['as' => 'config.index', 'uses' => 'ConfigController@index']);
    Route::get('config/{id}/edit', ['as' => 'config.edit', 'uses' => 'ConfigController@edit']);
    Route::post('config/{id}', ['as' => 'config.update', 'uses' => 'ConfigController@update']);
});
