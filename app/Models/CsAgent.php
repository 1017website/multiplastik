<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsAgent extends Model
{
    protected $fillable = [
        'name', 'whatsapp', 'display_number', 'greeting',
        'avatar', 'sort_order', 'is_active', 'click_count',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function clicks()
    {
        return $this->hasMany(CsClick::class);
    }

    public function getWaLinkAttribute(): string
    {
        $num = preg_replace('/[^0-9]/', '', $this->whatsapp);
        $msg = $this->greeting ?: 'Halo, saya ingin bertanya tentang produk Multi Plastik.';
        return 'https://wa.me/' . $num . '?text=' . urlencode($msg);
    }
}
