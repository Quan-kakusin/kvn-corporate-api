<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SeoController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SubscriberController;
use App\Http\Controllers\Integration\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/categories', [NewsController::class, 'getByCategory']);
Route::get('/news/{slug}', [NewsController::class, 'show']);

Route::get('/services', [ServiceController::class, 'index']);
Route::post('/subscriber', [SubscriberController::class, 'store']);
Route::post('/contact', [ContactController::class, 'submitContact'])->middleware('throttle:3,1');
Route::post('/webhook/new-post', [WebhookController::class, 'handleNewNews']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

Route::get('/seo/pages', [SeoController::class, 'getStaticPages']);
