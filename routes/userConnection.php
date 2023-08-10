<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::group(
    [
        'middleware' => ['auth'],
    ],
    function () {

        Route::get('home', [HomeController::class, 'index'])->name('home');

        Route::controller(UserController::class)->prefix('user')->as('user.')->group(function () {
            Route::get('/count', 'countUsers')->name('count');
            Route::get('/suggestions', 'getSuggestions')->name('suggestions');
            Route::get('/send-request/{targetUser}', 'sendConnectionRequest')->name('send-connection');
            Route::get('/withdraw-request/{targetUser}', 'withdrawConnectionRequest')->name('withdraw-connection');
            Route::get('/sent-requests', 'getSentRequests')->name('sent-requests');
            Route::get('/accept-request/{connectionRequest}', 'acceptConnectionRequest')->name('accept-connection');
            Route::get('/received-requests', 'getReceivedRequests')->name('received-requests');
            Route::get('/connections', 'getConnections')->name('connections');
            Route::get('/remove-connection/{connectionUser}', 'removeConnection')->name('remove-connection');
            Route::get('/common-connections/{otherUser}', 'getCommonConnections')->name('common-connections');
        });
    }
);
