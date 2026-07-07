<?php

use App\Http\Controllers\BannersController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProductController;

Route::get('/banners', [BannersController::class, 'getBanners']);
Route::get('/news', [NewsController::class, 'index']);

Route::get('/news/categories', [NewsController::class, 'getByCategory']);

Route::get('/news/{slug}', [NewsController::class, 'show']);

Route::get('/services', [ServiceController::class, 'index']);
Route::post('/subscriber', [SubscriberController::class, 'store']);
Route::post('/contact', [ContactController::class, 'submitContact'])->middleware('throttle:3,1');
Route::post('/webhook/new-post', [WebhookController::class, 'handleNewNews']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
