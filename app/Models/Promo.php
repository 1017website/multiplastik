<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = ['text', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
}
