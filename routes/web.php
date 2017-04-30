<?php

require_once app_path('Http/helpers.php');

Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

Route::resource('autoresponders', 'AutorespondersController');
Route::resource('contacts', 'ContactsController');
Route::resource('newsletters', 'NewslettersController');
Route::resource('templates', 'TemplatesController');
