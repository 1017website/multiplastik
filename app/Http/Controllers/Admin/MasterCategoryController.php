<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MasterCategoryController extends Controller
{
    public function index()
    {
        $masterCategories = MasterCategory::withCount(['categories', 'products'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.master-categories.index', compact('masterCategories'));
    }

    public function create()
    {
        return view('admin.master-categories.form', [
            'masterCategory' => new MasterCategory,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['image'] = $this->handleUpload($request);
        $data['is_active'] = $request->boolean('is_active');

        MasterCategory::create($data);

        return redirect()->route('admin.master-categories.index')
            ->with('success', 'Master kategori ditambahkan.');
    }

    public function edit(MasterCategory $masterCategory)
    {
        return view('admin.master-categories.form', compact('masterCategory'));
    }

    public function update(Request $request, MasterCategory $masterCategory)
    {
        $data = $this->validateData($request, $masterCategory);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $newImage = $this->handleUpload($request);

        if ($newImage) {
            $data['image'] = $newImage;
        }

        $data['is_active'] = $request->boolean('is_active');
        $masterCategory->update($data);

        return redirect()->route('admin.master-categories.index')
            ->with('success', 'Master kategori diperbarui.');
    }

    public function destroy(MasterCategory $masterCategory)
    {
        if ($masterCategory->categories()->exists()) {
            return back()->with('error', 'Master kategori masih digunakan. Pindahkan kategori brand terlebih dahulu.');
        }

        $masterCategory->delete();

        return back()->with('success', 'Master kategori dihapus.');
    }

    private function validateData(Request $request, ?MasterCategory $masterCategory = null): array
    {
        $request->merge([
            'slug' => $request->input('slug') ?: Str::slug($request->input('name', '')),
        ]);

        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => [
                'required',
                'string',
                'max:150',
                Rule::unique('master_categories', 'slug')->ignore($masterCategory),
            ],
            'icon' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'image' => ['nullable', 'image', 'max:3072'],
            'image_url_manual' => ['nullable', 'string', 'max:500'],
        ]);
    }

    private function handleUpload(Request $request): ?string
    {
        if ($request->filled('image_url_manual')) {
            return $request->input('image_url_manual');
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time().'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/master-categories'), $name);

            return $name;
        }

        return null;
    }
}
