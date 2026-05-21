<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = [
        'tag', 'title_top', 'title_em', 'title_bottom', 'subtitle',
        'background_image',
        'btn_primary_text', 'btn_primary_link', 'btn_primary_icon',
        'btn_secondary_text', 'btn_secondary_link', 'btn_secondary_icon',
        'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function getBackgroundUrlAttribute(): ?string
    {
        if (!$this->background_image) return null;
        if (str_starts_with($this->background_image, 'http')) return $this->background_image;
        return asset('uploads/slides/' . $this->background_image);
    }
}
