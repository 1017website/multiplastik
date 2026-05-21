<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    protected $fillable = ['slug', 'category', 'title', 'excerpt', 'image', 'content', 'published_at', 'is_active'];

    protected $casts = [
        'published_at' => 'date',
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        if (str_starts_with($this->image, 'http')) return $this->image;
        return asset('uploads/news/' . $this->image);
    }

    public function getDateFormattedAttribute(): string
    {
        if (!$this->published_at) return '';
        $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        return $this->published_at->day . ' ' . $bulan[$this->published_at->month - 1] . ' ' . $this->published_at->year;
    }
}
