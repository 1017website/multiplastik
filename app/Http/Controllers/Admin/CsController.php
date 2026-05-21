<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CsAgent;
use App\Models\CsClick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CsController extends Controller
{
    public function index()
    {
        $agents = CsAgent::withCount('clicks')->orderBy('sort_order')->get();

        // stats 30 hari
        $stats = CsClick::select('cs_agent_id', DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('cs_agent_id')
            ->pluck('total', 'cs_agent_id');

        // chart 7 hari per agent
        $chartDays = [];
        for ($i = 6; $i >= 0; $i--) {
            $chartDays[] = now()->subDays($i)->format('d M');
        }

        return view('admin.cs.index', compact('agents', 'stats', 'chartDays'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'whatsapp'       => 'required|string|max:20',
            'display_number' => 'nullable|string|max:30',
            'greeting'       => 'nullable|string|max:500',
            'sort_order'     => 'nullable|integer',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        CsAgent::create($data);
        return back()->with('success', 'CS ditambahkan.');
    }

    public function update(Request $request, CsAgent $csAgent)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'whatsapp'       => 'required|string|max:20',
            'display_number' => 'nullable|string|max:30',
            'greeting'       => 'nullable|string|max:500',
            'sort_order'     => 'nullable|integer',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $csAgent->update($data);
        return back()->with('success', 'CS diperbarui.');
    }

    public function destroy(CsAgent $csAgent)
    {
        $csAgent->delete();
        return back()->with('success', 'CS dihapus.');
    }

    /**
     * API: ambil CS berikutnya (round-robin berdasarkan click_count terendah)
     * + catat click
     */
    public function next(Request $request)
    {
        $agents = CsAgent::where('is_active', true)->orderBy('click_count')->orderBy('sort_order')->get();

        if ($agents->isEmpty()) {
            return response()->json(['error' => 'No CS available'], 404);
        }

        $agent = $agents->first();

        // detect device
        $ua = $request->userAgent() ?? '';
        $device = preg_match('/mobile|android|iphone/i', $ua) ? 'mobile' : 'desktop';

        // log click
        CsClick::create([
            'cs_agent_id' => $agent->id,
            'ip'          => $request->ip(),
            'page'        => $request->input('page', '/'),
            'device'      => $device,
        ]);

        // increment counter
        $agent->increment('click_count');

        return response()->json([
            'id'      => $agent->id,
            'name'    => $agent->name,
            'wa_link' => $agent->wa_link,
            'display' => $agent->display_number ?: $agent->whatsapp,
        ]);
    }
}
