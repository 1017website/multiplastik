<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterCategory extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'icon',
        'image',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function categories()
    {
        return $this->hasMany(Category::class)->orderBy('sort_order');
    }

    public function activeCategories()
    {
        return $this->hasMany(Category::class)
            ->where('categories.is_active', true)
            ->whereHas('brand', fn ($query) => $query->where('is_active', true))
            ->orderBy('sort_order');
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, Category::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return asset('uploads/master-categories/'.$this->image);
    }
}
