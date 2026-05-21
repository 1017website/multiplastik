<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Slide;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        $slides = Slide::where('is_active', true)->orderBy('sort_order')->get();
        $promos = Promo::where('is_active', true)->orderBy('sort_order')->pluck('text')->toArray();
        $brands = Brand::where('is_active', true)
            ->withCount('activeCategories')
            ->orderBy('sort_order')->get();
        $news = News::where('is_active', true)->orderByDesc('published_at')->limit(3)->get();

        return view('site.home', compact('slides', 'promos', 'brands', 'news'));
    }

    public function brands()
    {
        $brands = Brand::where('is_active', true)
            ->withCount('activeCategories')
            ->with(['activeCategories' => fn($q) => $q->withCount('activeProducts')])
            ->orderBy('sort_order')->get();

        return view('site.brands', compact('brands'));
    }

    public function brandDetail(string $brandSlug)
    {
        $brand = Brand::where('slug', $brandSlug)->where('is_active', true)
            ->with(['activeCategories' => fn($q) => $q->withCount('activeProducts')])
            ->firstOrFail();

        return view('site.brand-detail', compact('brand'));
    }

    public function category(string $brandSlug, string $catSlug)
    {
        $brand = Brand::where('slug', $brandSlug)->where('is_active', true)->firstOrFail();
        $category = Category::where('brand_id', $brand->id)->where('slug', $catSlug)
            ->where('is_active', true)
            ->with('activeProducts')
            ->firstOrFail();

        return view('site.category', compact('brand', 'category'));
    }

    public function product(string $brandSlug, string $catSlug, string $prodSlug)
    {
        $brand = Brand::where('slug', $brandSlug)->where('is_active', true)->firstOrFail();
        $category = Category::where('brand_id', $brand->id)->where('slug', $catSlug)
            ->where('is_active', true)->firstOrFail();
        $product = Product::where('category_id', $category->id)->where('slug', $prodSlug)
            ->where('is_active', true)
            ->with('images')
            ->firstOrFail();

        $related = Product::where('category_id', $category->id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)->get();

        return view('site.product', compact('brand', 'category', 'product', 'related'));
    }

    public function news()
    {
        $news = News::where('is_active', true)->orderByDesc('published_at')->paginate(9);
        return view('site.news', compact('news'));
    }

    public function newsDetail(string $slug)
    {
        $article = News::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $recent = News::where('is_active', true)
            ->where('id', '!=', $article->id)
            ->orderByDesc('published_at')->limit(4)->get();

        return view('site.news-detail', compact('article', 'recent'));
    }

    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));
        $results = collect();

        if (strlen($q) >= 2) {
            $results = Product::where('is_active', true)
                ->where(fn($query) => $query
                    ->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%"))
                ->with('category.brand')
                ->limit(60)->get();
        }

        return view('site.search', compact('q', 'results'));
    }
}