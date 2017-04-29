<?php

require_once app_path('Http/helpers.php');

Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

Route::resource('newsletters', 'NewslettersController');
Route::resource('autoresponders', 'AutorespondersController');
Route::resource('templates', 'TemplatesController');
Route::resource('contacts', 'ContactsController');
