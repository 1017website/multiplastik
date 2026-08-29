<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnershipApplication extends Model
{
    public const STATUSES = [
        'new' => 'Baru',
        'contacted' => 'Dihubungi',
        'qualified' => 'Potensial',
        'completed' => 'Selesai',
        'rejected' => 'Ditolak',
    ];

    public const BUSINESS_STAGES = [
        'planning' => 'Baru merencanakan usaha',
        'offline_store' => 'Sudah memiliki toko offline',
        'online_reseller' => 'Sudah berjualan online/reseller',
        'wholesale' => 'Sudah menjalankan usaha grosir/distributor',
    ];

    public const CUSTOMER_TYPES = [
        'end_user' => 'End User',
        'retail' => 'Retail',
    ];

    public const CAPITAL_RANGES = [
        'under_10' => 'Di bawah Rp10 juta',
        '10_25' => 'Rp10–25 juta',
        '25_50' => 'Rp25–50 juta',
        '50_100' => 'Rp50–100 juta',
        'over_100' => 'Di atas Rp100 juta',
    ];

    public const START_TIMELINES = [
        'immediately' => 'Secepatnya',
        '1_3_months' => '1–3 bulan',
        '3_6_months' => '3–6 bulan',
        'over_6_months' => 'Lebih dari 6 bulan',
    ];

    public const PREFERRED_PRODUCTS = [
        'Gelas Plastik' => 'Gelas Plastik',
        'Gelas Printing' => 'Gelas Printing',
        'Tusuk Bambu' => 'Tusuk Bambu',
        'Sendok Plastik' => 'Sendok Plastik',
        'Styrofoam' => 'Styrofoam',
        'Produk Lainnya' => 'Produk Lainnya',
    ];

    protected $fillable = [
        'name', 'whatsapp', 'email', 'city', 'address', 'business_stage',
        'capital_range', 'start_timeline', 'preferred_products', 'message',
        'status', 'admin_notes', 'source_url', 'consent_at',
    ];

    protected $casts = [
        'preferred_products' => 'array',
        'consent_at' => 'datetime',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getBusinessStageLabelAttribute(): string
    {
        return self::businessStages()[$this->business_stage]
            ?? self::BUSINESS_STAGES[$this->business_stage]
            ?? $this->business_stage;
    }

    public function getCustomerTypeLabelAttribute(): string
    {
        return self::customerTypes()[$this->business_stage]
            ?? self::BUSINESS_STAGES[$this->business_stage]
            ?? $this->business_stage;
    }

    public function getCapitalRangeLabelAttribute(): string
    {
        return self::capitalRanges()[$this->capital_range]
            ?? self::CAPITAL_RANGES[$this->capital_range]
            ?? $this->capital_range;
    }

    public function getStartTimelineLabelAttribute(): string
    {
        return self::startTimelines()[$this->start_timeline]
            ?? self::START_TIMELINES[$this->start_timeline]
            ?? $this->start_timeline;
    }

    public function getPreferredProductLabelsAttribute(): array
    {
        $configured = self::preferredProducts();

        return array_map(
            fn (string $product) => $configured[$product]
                ?? self::PREFERRED_PRODUCTS[$product]
                ?? $product,
            $this->preferred_products ?? []
        );
    }

    public static function businessStages(): array
    {
        return self::configuredOptions('partnership_business_stages', self::BUSINESS_STAGES);
    }

    public static function customerTypes(): array
    {
        return self::configuredOptions('partnership_customer_types', self::CUSTOMER_TYPES);
    }

    public static function capitalRanges(): array
    {
        return self::configuredOptions('partnership_capital_ranges', self::CAPITAL_RANGES);
    }

    public static function startTimelines(): array
    {
        return self::configuredOptions('partnership_start_timelines', self::START_TIMELINES);
    }

    public static function preferredProducts(): array
    {
        return self::configuredOptions('partnership_preferred_products', self::PREFERRED_PRODUCTS);
    }

    public static function fieldLabels(): array
    {
        return [
            'customer_type' => SiteSetting::get('partnership_customer_type_label', 'Jenis Pelanggan'),
        ];
    }

    public static function optionsAsText(array $options): string
    {
        return collect($options)
            ->map(fn (string $label, string $value) => $value.'|'.$label)
            ->implode("\n");
    }

    public static function parseOptions(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        $options = [];

        foreach (preg_split('/\R/u', $value) ?: [] as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            [$optionValue, $label] = str_contains($line, '|')
                ? array_map('trim', explode('|', $line, 2))
                : [$line, $line];

            if ($optionValue !== '' && $label !== '') {
                $options[$optionValue] = $label;
            }
        }

        return $options;
    }

    private static function configuredOptions(string $key, array $defaults): array
    {
        $configured = self::parseOptions(SiteSetting::get($key));

        return $configured ?: $defaults;
    }
}
