<?php
use App\Http\Controllers\Api\NavigationController;
use App\Http\Controllers\Api\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/navigation/{location}', [NavigationController::class, 'byLocation']);
Route::get('/site/header-topbar', [SiteController::class, 'headerTopBar']);
