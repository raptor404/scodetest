<?php
use Illuminate\Support\Facades\Route;


Route::post('/senator/email', [\App\Http\Controllers\Api\SenatorEmailController::class, 'send'])
    ->middleware(\App\Http\Middleware\SignatureCheck::class);;
