<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['slug', 'name', 'tagline', 'logo', 'description', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

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
