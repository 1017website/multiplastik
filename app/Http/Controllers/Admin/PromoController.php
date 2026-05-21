<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::orderBy('sort_order')->get();
        return view('admin.promos.index', compact('promos'));
    }

    public function store(Request $request)
    {
        $request->validate(['text' => 'required|string|max:255']);
        Promo::create([
            'text' => $request->text,
            'sort_order' => (int)$request->input('sort_order', 0),
            'is_active' => $request->boolean('is_active', true),
        ]);
        return back()->with('success', 'Promo ditambahkan.');
    }

    public function update(Request $request, Promo $promo)
    {
        $request->validate(['text' => 'required|string|max:255']);
        $promo->update([
            'text' => $request->text,
            'sort_order' => (int)$request->input('sort_order', 0),
            'is_active' => $request->boolean('is_active'),
        ]);
        return back()->with('success', 'Promo diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return back()->with('success', 'Promo dihapus.');
    }
}
