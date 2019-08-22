<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Auth
Auth::routes(['verify' => true]);

// Subscriptions
Route::get('unsubscribe/{subscriberHash}', 'SubscriptionsController@unsubscribe')->name('subscriptions.unsubscribe');
Route::get('subscribe/{subscriberHash}', 'SubscriptionsController@subscribe')->name('subscriptions.subscribe');
Route::put('subscriptions/{subscriberId}', 'SubscriptionsController@update')->name('subscriptions.update');

// App
Route::middleware(['auth', 'verified'])->group(function()
{
    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('', 'Auth\ProfileController@edit')->name('edit');
        Route::put('', 'Auth\ProfileController@update')->name('update');
    });
    Route::get('/logout', ['as' => 'dashboard', 'uses' => 'Auth\LoginController@logout']);

    Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

    // Messages
    Route::get('messages', ['as' => 'messages.index', 'uses' => 'MessagesController@index']);
    Route::get('messages/draft', ['as' => 'messages.draft', 'uses' => 'MessagesController@draft']);
    Route::get('messages/{id}/show', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
    Route::post('messages/send', ['as' => 'messages.send', 'uses' => 'MessagesController@send']);

    // Automations
    Route::resource('automations', 'AutomationsController');
    Route::resource('automations.steps', 'AutomationStepsController')->except([
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
    Route::get('campaigns/{id}/template', 'CampaignsController@selectTemplate')->name('campaigns.template.create');
    Route::put('campaigns/{id}/template', 'CampaignsController@updateTemplate')->name('campaigns.template.update');
    Route::get('campaigns/{id}/content', 'CampaignsController@editContent')->name('campaigns.content.edit');
    Route::put('campaigns/{id}/content', 'CampaignsController@updateContent')->name('campaigns.content.update');
    Route::get('campaigns/{id}/confirm', 'CampaignsController@confirm')->name('campaigns.confirm');
    Route::put('campaigns/{id}/send', 'CampaignsController@send')->name('campaigns.send');
    Route::get('campaigns/{id}/status', ['as' => 'campaigns.status', 'uses' => 'CampaignsController@status']);
    Route::get('campaigns/{id}/report', ['as' => 'campaigns.report', 'uses' => 'CampaignReportsController@report']);

    // Templates
    Route::resource('templates', 'TemplatesController')->except(['show']);

    // Providers
    Route::get('providers', ['as' => 'providers.index', 'uses' => 'ProvidersController@index']);
    Route::get('providers/create', ['as' => 'providers.create', 'uses' => 'ProvidersController@create']);
    Route::get('providers/type/{id}', ['as' => 'providers.ajax', 'uses' => 'ProvidersController@providersTypeAjax']);
    Route::post('providers', ['as' => 'providers.store', 'uses' => 'ProvidersController@store']);
    Route::get('providers/{id}/edit', ['as' => 'providers.edit', 'uses' => 'ProvidersController@edit']);
    Route::post('providers/{id}', ['as' => 'providers.update', 'uses' => 'ProvidersController@update']);
    Route::delete('providers/{id}', ['as' => 'providers.delete', 'uses' => 'ProvidersController@delete']);

    // Ajax
    Route::namespace('Ajax')->prefix('ajax')->group(function ()
    {
        Route::post('segments/store', 'SegmentsController@store')->name('ajax.segments.store');
    });
});
