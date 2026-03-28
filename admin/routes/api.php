<?php
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\Pages\ShortcodeController;
use App\Http\Controllers\Api\Pages\ContactController;
use App\Http\Controllers\Api\WidgetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Pages\PageDetailController;

// -------------------Layout
Route::prefix('/')->group(function () {
    Route::get('menus', [MenuController::class, 'getMenus']); // ?locale=vi
    Route::prefix('widgets')->group(function () {
        Route::get('layout', [WidgetController::class, 'getLayoutWidgets']); // ?locale=vi
        Route::get('sidebar', [WidgetController::class, 'getSidebarWidgets']); // ?locale=vi&type=blog
    });
});

// -------------------Pages (Dynamic by Slug)
Route::prefix('pages')->group(function () {
    // Universal dynamic endpoints
    Route::get('{slug}/meta',     [ShortcodeController::class, 'getMeta']);       // ?locale=vi
    Route::get('{slug}/sections', [ShortcodeController::class, 'getSections']);   // ?locale=vi → trả tất cả sections
    
    // Specific Post Actions
    Route::post('contact/section/form', [ContactController::class, 'submitSectionFormContact']);

    //Pages Details
    Route::get('{slug}/details', [PageDetailController::class, 'getDetails']);   // ?locale=vi → trả tất cả sections
});
Route::prefix('blog')->group(function () {
    Route::get('listing', [BlogController::class, 'getListing']);
});
