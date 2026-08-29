<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\PartnershipApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PartnershipController extends Controller
{
    public function create(): View
    {
        return view('site.partnership', [
            'customerTypes' => PartnershipApplication::customerTypes(),
            'fieldLabels' => PartnershipApplication::fieldLabels(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $customerTypes = PartnershipApplication::customerTypes();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'whatsapp' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{8,30}$/'],
            'email' => ['nullable', 'email', 'max:190'],
            'city' => ['required', 'string', 'max:120'],
            'address' => ['required', 'string', 'max:1000'],
            'customer_type' => ['required', Rule::in(array_keys($customerTypes))],
            'message' => ['nullable', 'string', 'max:2000'],
            'website' => ['prohibited'],
        ], [
            'whatsapp.regex' => 'Format nomor WhatsApp belum sesuai.',
            'website.prohibited' => 'Form tidak dapat diproses.',
        ]);

        $data['whatsapp'] = $this->normalizeWhatsapp($data['whatsapp']);
        $data['business_stage'] = $data['customer_type'];
        $data['capital_range'] = 'not_applicable';
        $data['start_timeline'] = 'not_applicable';
        $data['preferred_products'] = [];
        $data['source_url'] = $request->headers->get('referer');
        $data['consent_at'] = now();
        unset($data['customer_type'], $data['website']);

        PartnershipApplication::create($data);

        return redirect()
            ->route('site.partnership')
            ->with('partnership_success', 'Terima kasih. Tim Multi Plastik akan menghubungi Anda melalui WhatsApp.');
    }

    private function normalizeWhatsapp(string $number): string
    {
        $number = preg_replace('/\D+/', '', $number);

        if (str_starts_with($number, '0')) {
            return '62'.substr($number, 1);
        }

        return $number;
    }
}
