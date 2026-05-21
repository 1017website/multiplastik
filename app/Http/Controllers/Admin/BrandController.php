<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('categories')->orderBy('sort_order')->paginate(20);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.form', ['brand' => new Brand()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['logo'] = $this->handleUpload($request, 'logo', 'brands');
        $data['is_active'] = $request->boolean('is_active');
        Brand::create($data);
        return redirect()->route('admin.brands.index')->with('success', 'Brand ditambahkan.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.form', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $data = $this->validateData($request, $brand->id);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $newLogo = $this->handleUpload($request, 'logo', 'brands');
        if ($newLogo) $data['logo'] = $newLogo;
        $data['is_active'] = $request->boolean('is_active');
        $brand->update($data);
        return redirect()->route('admin.brands.index')->with('success', 'Brand diperbarui.');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return back()->with('success', 'Brand dihapus.');
    }

    private function validateData(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:brands,slug' . ($ignoreId ? ',' . $ignoreId : ''),
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'logo' => 'nullable|image|max:2048',
            'logo_url_manual' => 'nullable|string|max:500',
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
