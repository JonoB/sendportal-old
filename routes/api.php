<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::name('api.')->group(function()
{
    Route::apiResource('subscribers', 'SubscribersController');
    Route::apiResource('segments', 'SegmentsController');

    Route::apiResource('subscribers.segments', 'SubscriberSegmentsController')
        ->except(['show', 'update', 'destroy']);
    Route::put('subscribers/{subscriber}/segments', 'SubscriberSegmentsController@update')
        ->name('subscribers.segments.update');
    Route::delete('subscribers/{subscriber}/segments', 'SubscriberSegmentsController@destroy')
        ->name('subscribers.segments.destroy');

    Route::apiResource('segments.subscribers', 'SegmentSubscribersController')
        ->except(['show', 'update', 'destroy']);
    Route::put('segments/{segment}/subscribers', 'SegmentSubscribersController@update')
        ->name('segments.subscribers.update');
    Route::delete('segments/{segment}/subscribers', 'SegmentSubscribersController@destroy')
        ->name('segments.subscribers.destroy');
});

Route::post('webhooks/aws', 'AwsWebhooksController@handle');
Route::post('webhooks/mailgun', 'MailgunWebhooksController@handle');