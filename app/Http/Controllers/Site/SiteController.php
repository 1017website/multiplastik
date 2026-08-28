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
        $categories = Category::query()
            ->select('categories.*')
            ->join('brands', 'brands.id', '=', 'categories.brand_id')
            ->where('categories.is_active', true)
            ->where('brands.is_active', true)
            ->with('brand')
            ->withCount('activeProducts')
            ->orderBy('brands.sort_order')
            ->orderBy('categories.sort_order')
            ->orderBy('categories.name')
            ->get();
        $news = News::where('is_active', true)->orderByDesc('published_at')->limit(3)->get();

        return view('site.home', compact('slides', 'promos', 'brands', 'categories', 'news'));
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

    public function liveSearch(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['count' => 0, 'items' => []]);
        }

        $products = Product::where('is_active', true)
            ->where(fn($query) => $query
                ->where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%"))
            ->with('category.brand')
            ->limit(8)->get();

        $items = $products->map(function ($p) {
            $brand = $p->category?->brand;
            return [
                'name'     => $p->name,
                'brand'    => $brand?->name ?? '',
                'category' => $p->category?->name ?? '',
                'desc'     => $p->description ? \Illuminate\Support\Str::limit(strip_tags($p->description), 60) : '',
                'image'    => $p->image_url,
                'url'      => ($brand && $p->category)
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
