<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtisanLog extends Model
{
    protected $fillable = ['user_id', 'command', 'output', 'success', 'duration'];
    protected $casts = ['success' => 'boolean'];
}
