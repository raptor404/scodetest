<?php

use Illuminate\Support\Facades\Route;
Route::view('404', 'errors.404');
Route::view('403', 'errors.403');

Route::view('/', 'welcome');
//If we had environment data or a public key system we would add the middleware here to prevent people from using our dev method, which is trivial
Route::group(['prefix' => 'dev','middleware'=>\App\Http\Middleware\LowerEnvironmentOnly::class], function () {
    Route::get('/email-form', [\App\Http\Controllers\Dev\TestController::class, 'emailForm']);
});

