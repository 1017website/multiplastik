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
        return self::BUSINESS_STAGES[$this->business_stage] ?? $this->business_stage;
    }

    public function getCapitalRangeLabelAttribute(): string
    {
        return self::CAPITAL_RANGES[$this->capital_range] ?? $this->capital_range;
    }

    public function getStartTimelineLabelAttribute(): string
    {
        return self::START_TIMELINES[$this->start_timeline] ?? $this->start_timeline;
    }
}
