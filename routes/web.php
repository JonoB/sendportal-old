<?php

require_once app_path('Http/helpers.php');

Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

Route::resource('autoresponders', 'AutorespondersController');

Route::resource('contacts', 'ContactsController');
Route::resource('segments', 'SegmentsController');

Route::resource('newsletters', 'NewslettersController');
Route::get('newsletters/{id}/template', ['as' => 'newsletters.template', 'uses' => 'NewslettersController@template']);
Route::put('newsletters/{id}/template', ['as' => 'newsletters.template.update', 'uses' => 'NewslettersController@updateTemplate']);
Route::get('newsletters/{id}/design', ['as' => 'newsletters.design', 'uses' => 'NewslettersController@design']);
Route::put('newsletters/{id}/design', ['as' => 'newsletters.design.update', 'uses' => 'NewslettersController@updateDesign']);
Route::get('newsletters/{id}/confirm', ['as' => 'newsletters.confirm', 'uses' => 'NewslettersController@confirm']);
Route::put('newsletters/{id}/send', ['as' => 'newsletters.send', 'uses' => 'NewslettersController@send']);
Route::get('newsletters/{id}/status', ['as' => 'newsletters.status', 'uses' => 'NewslettersController@status']);

Route::get('newsletters/{id}/report', ['as' => 'newsletters.report', 'uses' => 'NewsletterReportsController@report']);
Route::get('newsletters/{id}/recipients', ['as' => 'newsletters.recipients', 'uses' => 'NewsletterReportsController@recipients']);

Route::resource('templates', 'TemplatesController');

Route::get('tracker/opens/{newsletterId}/{contactId}', ['as' => 'tracker.opens', 'uses' => 'TrackerController@opens']);
Route::get('tracker/clicks/{newsletterId}/{contactId}/{linkId}', ['as' => 'tracker.clicks', 'uses' => 'TrackerController@clicks']);

Route::get('unsubscribe/{contactId}', ['as' => 'subscriptions.unsubscribe', 'uses' => 'SubscriptionsController@unsubscribe']);
Route::post('subscriptions', ['as' => 'subscriptions.update', 'uses' => 'SubscriptionsController@update']);
Route::get('subscribe/{contactId}', ['as' => 'subscriptions.subscribe', 'uses' => 'SubscriptionsController@subscribe']);
