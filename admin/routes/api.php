<?php
use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\BlogController;
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
   Route::get('home/section/site-statistics', [HomeController::class,'getSectionSiteStatistics']);
   Route::get('home/section/services', [HomeController::class,'getSectionServices']);
   Route::get('home/section/include-webdemo', [HomeController::class,'getSectionIncludeWebdemo']);
   Route::get('home/section/about-us-information', [HomeController::class,'getSectionAboutUsInformation']);
   Route::get('home/section/contact-block', [HomeController::class,'getSectionContactBlock']);
   Route::get('home/section/testimonials', [HomeController::class,'getSectionTestimonials']);
   Route::get('home/section/team', [HomeController::class,'getSectionTeam']);
   Route::get('home/section/faqs', [HomeController::class,'getSectionFaqs']);
   Route::get('home/section/blog-posts', [HomeController::class,'getSectionBlogPosts']);

Route::prefix('blog')->group(function () {
    Route::get('/',[BlogController::class, 'getBlogs']);
});
