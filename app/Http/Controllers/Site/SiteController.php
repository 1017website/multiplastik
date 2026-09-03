<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MasterCategory;
use App\Models\News;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function home()
    {
        $showCategorySection = setting_enabled('home_category_section_active');
        $showBrandSection = setting_enabled('home_brand_section_active');
        $slides = Slide::where('is_active', true)->orderBy('sort_order')->get();
        $promos = Promo::where('is_active', true)->orderBy('sort_order')->pluck('text')->toArray();
        $brands = $showBrandSection
            ? Brand::visibleOnFrontend()
                ->withCount('activeCategories')
                ->orderBy('sort_order')->get()
            : collect();
        $masterCategories = $showCategorySection
            ? MasterCategory::query()
                ->where('is_active', true)
                ->whereHas('activeCategories')
                ->with(['activeCategories' => fn ($query) => $query
                    ->with('brand')
                    ->withCount('activeProducts')])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
            : collect();
        $news = News::where('is_active', true)->orderByDesc('published_at')->limit(3)->get();

        return view('site.home', compact(
            'slides',
            'promos',
            'brands',
            'masterCategories',
            'news',
            'showCategorySection',
            'showBrandSection'
        ));
    }

    public function brands()
    {
        $brands = Brand::visibleOnFrontend()
            ->withCount('activeCategories')
            ->with(['activeCategories' => fn ($q) => $q->withCount('activeProducts')])
            ->orderBy('sort_order')->get();

        return view('site.brands', compact('brands'));
    }

    public function brandDetail(string $brandSlug)
    {
        $brand = Brand::where('slug', $brandSlug)->where('is_active', true)
            ->with(['activeCategories' => fn ($q) => $q->withCount('activeProducts')])
            ->firstOrFail();

        return view('site.brand-detail', compact('brand'));
    }

    public function category(string $brandSlug, string $catSlug)
    {
        $brand = Brand::where('slug', $brandSlug)->where('is_active', true)->firstOrFail();
        $category = Category::where('brand_id', $brand->id)->where('slug', $catSlug)
            ->where('is_active', true)
            ->with(['activeProducts', 'masterCategory'])
            ->firstOrFail();

        return view('site.category', compact('brand', 'category'));
    }

    public function masterCategory(MasterCategory $masterCategory)
    {
        abort_unless($masterCategory->is_active, 404);

        $masterCategory->load(['activeCategories' => fn ($query) => $query
            ->with('brand')
            ->withCount('activeProducts')
            ->orderBy('name')]);

        return view('site.master-category', compact('masterCategory'));
    }

    public function product(string $brandSlug, string $catSlug, string $prodSlug)
    {
        $brand = Brand::where('slug', $brandSlug)->where('is_active', true)->firstOrFail();
        $category = Category::where('brand_id', $brand->id)->where('slug', $catSlug)
            ->where('is_active', true)->with('masterCategory')->firstOrFail();
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
                ->where(fn ($query) => $query
                    ->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%"))
                ->with('category.brand')
                ->limit(60)->get();
        }

        return view('site.search', compact('q', 'results'));
    }

    public function liveSearch(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['count' => 0, 'items' => []]);
        }

        $products = Product::where('is_active', true)
            ->where(fn ($query) => $query
                ->where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%"))
            ->with('category.brand')
            ->limit(8)->get();

        $items = $products->map(function ($p) {
            $brand = $p->category?->brand;

            return [
                'name' => $p->name,
                'brand' => $brand?->name ?? '',
                'category' => $p->category?->name ?? '',
                'desc' => $p->description ? Str::limit(strip_tags($p->description), 60) : '',
                'image' => $p->image_url,
                'url' => ($brand && $p->category)
                    ? route('site.product', [$brand->slug, $p->category->slug, $p->slug])
                    : '#',
            ];
        });

        return response()->json([
            'count' => $items->count(),
            'items' => $items,
        ]);
    }
}
