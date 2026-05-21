<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Share ke semua view yang berada di folder "site.*"
        View::composer('site.*', function ($view) {
            $view->with('siteNavBrands', Brand::where('is_active', true)->orderBy('sort_order')->get());
        });
    }
}
