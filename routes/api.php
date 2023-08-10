<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth'])->group(function () {
    Route::get('/suggestions', 'UserController@suggestions');
    Route::post('/connect/{user}', 'UserController@sendConnectionRequest');
    Route::get('/sent-requests', 'UserController@sentRequests');
    Route::delete('/withdraw-request/{request}', 'UserController@withdrawRequest');
    Route::get('/received-requests', 'UserController@receivedRequests');
    Route::post('/accept-request/{request}', 'UserController@acceptRequest');
    Route::get('/connections', 'UserController@connections');
    Route::delete('/remove-connection/{user}', 'UserController@removeConnection');
    Route::get('/connections-in-common/{user}', 'UserController@connectionsInCommon');
});
