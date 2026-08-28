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
            'businessStages' => PartnershipApplication::BUSINESS_STAGES,
            'capitalRanges' => PartnershipApplication::CAPITAL_RANGES,
            'startTimelines' => PartnershipApplication::START_TIMELINES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'whatsapp' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{8,30}$/'],
            'email' => ['nullable', 'email', 'max:190'],
            'city' => ['required', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:1000'],
            'business_stage' => ['required', Rule::in(array_keys(PartnershipApplication::BUSINESS_STAGES))],
            'capital_range' => ['required', Rule::in(array_keys(PartnershipApplication::CAPITAL_RANGES))],
            'start_timeline' => ['required', Rule::in(array_keys(PartnershipApplication::START_TIMELINES))],
            'preferred_products' => ['nullable', 'array', 'max:6'],
            'preferred_products.*' => ['string', Rule::in([
                'Gelas Plastik', 'Gelas Printing', 'Tusuk Bambu',
                'Sendok Plastik', 'Styrofoam', 'Produk Lainnya',
            ])],
            'message' => ['nullable', 'string', 'max:2000'],
            'consent' => ['accepted'],
            'website' => ['prohibited'],
        ], [
            'whatsapp.regex' => 'Format nomor WhatsApp belum sesuai.',
            'consent.accepted' => 'Persetujuan pengolahan data wajib dicentang.',
            'website.prohibited' => 'Form tidak dapat diproses.',
        ]);

        $data['whatsapp'] = $this->normalizeWhatsapp($data['whatsapp']);
        $data['source_url'] = $request->headers->get('referer');
        $data['consent_at'] = now();
        unset($data['consent'], $data['website']);

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
