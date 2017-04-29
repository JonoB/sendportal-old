<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

Route::resource('newsletters', 'NewslettersController');
Route::resource('autoresponders', 'AutorespondersController');
Route::resource('templates', 'TemplatesController');
Route::resource('contacts', 'ContactsController');
