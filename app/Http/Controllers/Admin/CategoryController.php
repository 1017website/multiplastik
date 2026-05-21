<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $q = Category::with('brand')->withCount('products');
        if ($request->filled('brand_id')) $q->where('brand_id', $request->brand_id);
        $categories = $q->orderBy('brand_id')->orderBy('sort_order')->paginate(20)->withQueryString();
        $brands = Brand::orderBy('name')->get();
        return view('admin.categories.index', compact('categories', 'brands'));
    }

    public function create()
    {
        $brands = Brand::orderBy('name')->get();
        return view('admin.categories.form', ['category' => new Category(), 'brands' => $brands]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['image'] = $this->handleUpload($request, 'image', 'categories');
        $data['is_active'] = $request->boolean('is_active');
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori ditambahkan.');
    }

    public function edit(Category $category)
    {
        $brands = Brand::orderBy('name')->get();
        return view('admin.categories.form', compact('category', 'brands'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validateData($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $newImg = $this->handleUpload($request, 'image', 'categories');
        if ($newImg) $data['image'] = $newImg;
        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Kategori dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
            'image_url_manual' => 'nullable|string|max:500',
        ]);
    }

    private function handleUpload(Request $request, string $field, string $folder): ?string
    {
        if ($request->filled($field . '_url_manual')) {
            return $request->input($field . '_url_manual');
        }
        if ($request->hasFile($field)) {
            $file = $request->file($field);
            $name = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/' . $folder), $name);
            return $name;
        }
        return null;
    }
}
