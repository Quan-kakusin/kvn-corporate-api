<?php

use App\Http\Controllers\BannersController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\WebhookController;

Route::get('/banners', [BannersController::class, 'getBanners']);
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/categories', [NewsController::class, 'getByCategory']);
Route::post('/subscriber', [SubscriberController::class, 'store']);
Route::post('/webhook/new-post', [WebhookController::class, 'handleNewNews']);
