<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SlideController extends Controller
{
    public function index()
    {
        $slides = Slide::orderBy('sort_order')->paginate(20);
        return view('admin.slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.slides.form', ['slide' => new Slide()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['background_image'] = $this->handleUpload($request, 'background_image', 'slides');
        $data['is_active'] = $request->boolean('is_active');
        Slide::create($data);
        return redirect()->route('admin.slides.index')->with('success', 'Slide ditambahkan.');
    }

    public function edit(Slide $slide)
    {
        return view('admin.slides.form', compact('slide'));
    }

    public function update(Request $request, Slide $slide)
    {
        $data = $this->validateData($request);
        $newImg = $this->handleUpload($request, 'background_image', 'slides');
        if ($newImg) $data['background_image'] = $newImg;
        $data['is_active'] = $request->boolean('is_active');
        $slide->update($data);
        return redirect()->route('admin.slides.index')->with('success', 'Slide diperbarui.');
    }

    public function destroy(Slide $slide)
    {
        $slide->delete();
        return back()->with('success', 'Slide dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'tag' => 'nullable|string|max:100',
            'title_top' => 'nullable|string|max:150',
            'title_em' => 'nullable|string|max:150',
            'title_bottom' => 'nullable|string|max:150',
            'subtitle' => 'nullable|string|max:500',
            'background_image' => 'nullable|image|max:5120',
            'background_image_url_manual' => 'nullable|string|max:500',
            'btn_primary_text' => 'nullable|string|max:100',
            'btn_primary_link' => 'nullable|string|max:255',
            'btn_primary_icon' => 'nullable|string|max:50',
            'btn_secondary_text' => 'nullable|string|max:100',
            'btn_secondary_link' => 'nullable|string|max:255',
            'btn_secondary_icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
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
