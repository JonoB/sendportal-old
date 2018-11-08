<?php

// use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ajax Routes
|--------------------------------------------------------------------------
|
*/

Route::name('ajax.')->group(function ()
{
    Route::post('segments/store', 'SegmentsController@store')->name('segments.store');
});