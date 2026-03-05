<?php
use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\Pages\HomeController;
use Illuminate\Support\Facades\Route;

// -------------------Menu 
Route::prefix('common')->group(function () {
    Route::get('header',              [CommonController::class, 'getHeader']);   // ?locale=vi
    Route::get('navigation/{locale}', [CommonController::class, 'getMainMenu']); // /vi hoặc /en
    Route::get('footer',              [CommonController::class, 'getFooter']);
});
// -------------------Home Page 
Route::prefix('pages')->group(function () {
    Route::get('home/meta',              [HomeController::class, 'getMeta']);   // ?locale=vi
   Route::get('home/section/simple-slider', [HomeController::class,'getSectionSimpleSlider']);
   Route::get('home/section/services', [HomeController::class,'getSectionServices']);

});
