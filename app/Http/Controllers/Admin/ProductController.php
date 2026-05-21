<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = Product::with('category.brand');
        if ($request->filled('category_id')) $q->where('category_id', $request->category_id);
        if ($request->filled('search')) $q->where('name', 'like', '%' . $request->search . '%');
        $products = $q->orderBy('category_id')->orderBy('sort_order')->paginate(20)->withQueryString();
        $categories = Category::with('brand')->orderBy('name')->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::with('brand')->orderBy('name')->get();
        return view('admin.products.form', ['product' => new Product(), 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['image'] = $this->handleUpload($request, 'image', 'products');
        $data['specs'] = $this->parseSpecs($request);
        $data['is_active'] = $request->boolean('is_active');

        $product = Product::create($data);
        $this->handleGallery($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Produk ditambahkan.');
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::with('brand')->orderBy('name')->get();
        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $newImg = $this->handleUpload($request, 'image', 'products');
        if ($newImg) $data['image'] = $newImg;
        $data['specs'] = $this->parseSpecs($request);
        $data['is_active'] = $request->boolean('is_active');

        $product->update($data);
        $this->handleGallery($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Produk diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produk dihapus.');
    }

    public function deleteImage(ProductImage $image)
    {
        $image->delete();
        return back()->with('success', 'Gambar dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|max:3072',
            'image_url_manual' => 'nullable|string|max:500',
            'gallery_images.*' => 'nullable|image|max:3072',
            'gallery_urls' => 'nullable|string',
        ]);
    }

    /**
     * Specs format input: tiap baris "Label|Value"
     * disimpan sebagai array [["Ukuran","6 Oz"],["Material","PP"]]
     */
    private function parseSpecs(Request $request): array
    {
        $raw = $request->input('specs_text', '');
        $lines = preg_split('/\r\n|\r|\n/', trim($raw));
        $specs = [];
        foreach ($lines as $l) {
            if (!str_contains($l, '|')) continue;
            [$k, $v] = array_map('trim', explode('|', $l, 2));
            if ($k !== '' && $v !== '') $specs[] = [$k, $v];
        }
        return $specs;
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

    private function handleGallery(Request $request, Product $product): void
    {
        // file upload gallery
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $name = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $name);
                $product->images()->create(['path' => $name, 'sort_order' => 0]);
            }
        }
        // url manual (tiap baris)
        if ($request->filled('gallery_urls')) {
            $urls = preg_split('/\r\n|\r|\n/', trim($request->gallery_urls));
            foreach ($urls as $url) {
                $url = trim($url);
                if ($url && str_starts_with($url, 'http')) {
                    $product->images()->create(['path' => $url, 'sort_order' => 0]);
                }
            }
        }
    }
}
