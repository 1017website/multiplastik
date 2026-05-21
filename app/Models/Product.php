<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'slug', 'name', 'image', 'description', 'specs', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'specs' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        if (str_starts_with($this->image, 'http')) return $this->image;
        return asset('uploads/products/' . $this->image);
    }

    public function getGalleryAttribute(): array
    {
        $gallery = [];
        if ($this->image) $gallery[] = $this->image_url;
        foreach ($this->images as $img) {
            $gallery[] = str_starts_with($img->path, 'http')
                ? $img->path
                : asset('uploads/products/' . $img->path);
        }
        return array_unique($gallery);
    }
}
