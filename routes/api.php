<?php

use App\Http\Controllers\BannersController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/banners', [BannersController::class, 'getBanners']);
Route::get('/news', [NewsController::class, 'index']);
