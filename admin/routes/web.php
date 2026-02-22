<?php

use App\Http\Controllers\Admin\DemoWebsiteController;
use App\Http\Controllers\Client\ClDemoWebsiteController;
use App\Http\Middleware\MarketingLocaleMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/portfolio/website-demos')->group(function () {
    Route::get('/', [DemoWebsiteController::class, 'index'])->name('portfolio.websites.index');
    Route::get('create', [DemoWebsiteController::class, 'create'])->name('portfolio.websites.create');
    Route::post('store', [DemoWebsiteController::class, 'store'])->name('portfolio.websites.store');
    Route::get('edit/{id}', [DemoWebsiteController::class, 'edit'])->name('portfolio.websites.edit');
    Route::put('update/{id}', [DemoWebsiteController::class, 'update'])->name('portfolio.websites.update');
    Route::delete('destroy/{id}', [DemoWebsiteController::class, 'destroy'])->name('portfolio.websites.destroy');
});

// Route::prefix('website-demos')->group(function () {
//     Route::get('{slug}', [ClDemoWebsiteController::class, 'show'])->name('websites.show');
// });

// foreach (['website-demos', '{locale}/website-demos'] as $prefix) {
//     $isLocalized = $prefix !== 'website-demos';
//     $suffix = $isLocalized ? '.locale' : '';

//     Route::prefix($prefix)
//         ->middleware(MarketingLocaleMiddleware::class)
//         ->group(function () use ($suffix) {
//             Route::get('{slug}', function ($slug) use ($suffix) {
//                 dd("Đã khớp route websites.show{$suffix} với slug: {$slug}");
//             })->name('websites.show' . $suffix);
//         });
// }

// Route::get('{slug}', function ($slug) use ($suffix) {
//                 dd("Đã khớp route websites.show{$suffix} với slug: {$slug}");
//             })->name('websites.show' . $suffix);

// Route::get('{slug}', [ClDemoWebsiteController::class, 'show'])->name('websites.show' . $suffix);


// Route không có locale


// Route có locale
// Route::get('{locale}/website-demos/{slug}', function ($locale, $slug) {
//     dd("Đã khớp route websites.show.locale với locale: {$locale}, slug: {$slug}");
// })->middleware(MarketingLocaleMiddleware::class)
//     ->where('locale', '[a-zA-Z_-]+')
//     ->name('websites.show.locale');

// Route::get('website-demos/{slug}', [ClDemoWebsiteController::class, 'show'])
//     ->name('websites.show');
// Route::get('{locale}/website-demos/{slug}', [ClDemoWebsiteController::class, 'show'])
//     ->middleware(MarketingLocaleMiddleware::class)
//     ->where('locale', '[a-zA-Z_-]+')
//     ->name('websites.show.locale');

Route::get('website-demos/{slug}', [ClDemoWebsiteController::class, 'show'])
    ->name('websites.show');

// Có locale
Route::get('{locale}/website-demos/{slug}', function ($locale, $slug) {
    return app(App\Http\Controllers\Client\ClDemoWebsiteController::class)->show($slug, $locale);
})
    ->middleware(MarketingLocaleMiddleware::class)
    ->where('locale', '[a-zA-Z_-]+')
    ->name('websites.show.locale');
