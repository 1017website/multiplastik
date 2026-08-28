<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnershipApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PartnershipController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $search = trim($request->string('q')->toString());

        $applications = PartnershipApplication::query()
            ->when(array_key_exists($status, PartnershipApplication::STATUSES), fn ($query) => $query->where('status', $status))
            ->when($search !== '', fn ($query) => $query->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $statusCounts = PartnershipApplication::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.partnerships.index', [
            'applications' => $applications,
            'statuses' => PartnershipApplication::STATUSES,
            'statusCounts' => $statusCounts,
            'activeStatus' => $status,
            'search' => $search,
        ]);
    }

    public function show(PartnershipApplication $partnership): View
    {
        return view('admin.partnerships.show', [
            'partnership' => $partnership,
            'statuses' => PartnershipApplication::STATUSES,
        ]);
    }

    public function update(Request $request, PartnershipApplication $partnership): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(PartnershipApplication::STATUSES))],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $partnership->update($data);

        return back()->with('success', 'Data kemitraan diperbarui.');
    }
}
