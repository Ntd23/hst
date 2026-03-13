<?php
use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\Pages\ShortcodeController;
use App\Http\Controllers\Api\Pages\ContactController;
use Illuminate\Support\Facades\Route;

// -------------------Menu 
Route::prefix('common')->group(function () {
    Route::get('header',              [CommonController::class, 'getHeader']);   // ?locale=vi
    Route::get('navigation/{locale}', [CommonController::class, 'getMainMenu']); // /vi hoặc /en
    Route::get('footer',              [CommonController::class, 'getFooter']);
});

// -------------------Pages (Dynamic by Slug)
Route::prefix('pages')->group(function () {
    // Universal dynamic endpoints
    Route::get('{slug}/meta',     [ShortcodeController::class, 'getMeta']);       // ?locale=vi
    Route::get('{slug}/sections', [ShortcodeController::class, 'getSections']);   // ?locale=vi → trả tất cả sections
    
    // Specific Post Actions
    Route::post('contact/section/form', [ContactController::class, 'submitSectionFormContact']);
});
Route::prefix('blog')->group(function () {
    Route::get('/',[BlogController::class, 'getBlogs']);
});
