<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['slug', 'name', 'tagline', 'logo', 'description', 'sort_order', 'is_active', 'show_on_frontend'];

    protected $casts = ['is_active' => 'boolean', 'show_on_frontend' => 'boolean'];

    // This controls brand listings only; catalog access still uses is_active.
    public function scopeVisibleOnFrontend(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('show_on_frontend', true);
    }

    public function categories()
    {
        return $this->hasMany(Category::class)->orderBy('sort_order');
    }

    public function activeCategories()
    {
        return $this->hasMany(Category::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) return null;
        if (str_starts_with($this->logo, 'http')) return $this->logo;
        return asset('uploads/brands/' . $this->logo);
    }
}
