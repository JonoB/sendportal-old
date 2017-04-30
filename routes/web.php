<?php

require_once app_path('Http/helpers.php');

Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

Route::resource('autoresponders', 'AutorespondersController');

Route::resource('contacts', 'ContactsController');

Route::resource('newsletters', 'NewslettersController');
Route::get('newsletters/{id}/template', ['as' => 'newsletters.template', 'uses' => 'NewslettersController@template']);
Route::put('newsletters/{id}/template', ['as' => 'newsletters.template.update', 'uses' => 'NewslettersController@updateTemplate']);

Route::get('newsletters/{id}/design', ['as' => 'newsletters.design', 'uses' => 'NewslettersController@design']);
Route::put('newsletters/{id}/design', ['as' => 'newsletters.design.update', 'uses' => 'NewslettersController@updateDesign']);

Route::get('newsletters/{id}/confirm', ['as' => 'newsletters.confirm', 'uses' => 'NewslettersController@confirm']);
Route::put('newsletters/{id}/confirm', ['as' => 'newsletters.dispatch', 'uses' => 'NewslettersController@dispatch']);


Route::resource('templates', 'TemplatesController');
