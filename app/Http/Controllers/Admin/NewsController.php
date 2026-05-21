<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderByDesc('published_at')->paginate(20);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.form', ['news' => new News()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['image'] = $this->handleUpload($request, 'image', 'news');
        $data['is_active'] = $request->boolean('is_active');
        News::create($data);
        return redirect()->route('admin.news.index')->with('success', 'Artikel ditambahkan.');
    }

    public function edit(News $news)
    {
        return view('admin.news.form', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $data = $this->validateData($request, $news->id);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $newImg = $this->handleUpload($request, 'image', 'news');
        if ($newImg) $data['image'] = $newImg;
        $data['is_active'] = $request->boolean('is_active');
        $news->update($data);
        return redirect()->route('admin.news.index')->with('success', 'Artikel diperbarui.');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return back()->with('success', 'Artikel dihapus.');
    }

    private function validateData(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200|unique:news,slug' . ($ignoreId ? ',' . $ignoreId : ''),
            'category' => 'nullable|string|max:100',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'published_at' => 'nullable|date',
            'image' => 'nullable|image|max:3072',
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
