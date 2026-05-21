<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| FRONTEND (multi-page, URL terpisah, SEO friendly)
|--------------------------------------------------------------------------
*/
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/brand', [SiteController::class, 'brands'])->name('site.brands');
Route::get('/news', [SiteController::class, 'news'])->name('site.news');
Route::get('/news/{slug}', [SiteController::class, 'newsDetail'])->name('site.news.detail');
Route::get('/cari', [SiteController::class, 'search'])->name('site.search');
Route::get('/brand/{brand}', [SiteController::class, 'brandDetail'])->name('site.brand');
Route::get('/brand/{brand}/{category}', [SiteController::class, 'category'])->name('site.category');
Route::get('/brand/{brand}/{category}/{product}', [SiteController::class, 'product'])->name('site.product');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Protected
    Route::middleware('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Brands
        Route::resource('brands', BrandController::class)->except('show');

        // Categories
        Route::resource('categories', CategoryController::class)->except('show');

        // Products
        Route::resource('products', ProductController::class)->except('show');
        Route::delete('product-images/{image}', [ProductController::class, 'deleteImage'])->name('product-images.destroy');

        // News
        Route::resource('news', NewsController::class)->except('show');

        // Slides
        Route::resource('slides', SlideController::class)->except('show');

        // Promos
        Route::get('promos', [PromoController::class, 'index'])->name('promos.index');
        Route::post('promos', [PromoController::class, 'store'])->name('promos.store');
        Route::put('promos/{promo}', [PromoController::class, 'update'])->name('promos.update');
        Route::delete('promos/{promo}', [PromoController::class, 'destroy'])->name('promos.destroy');

        // Settings
        Route::get('settings/{group?}', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings/{group}', [SettingController::class, 'update'])->name('settings.update');

        // Analytics
        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

        // Users
        Route::resource('users', UserController::class)->except('show');
    });
});
