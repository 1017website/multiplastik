<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    protected $fillable = [
        'path', 'page_type', 'referrer',
        'utm_source', 'utm_medium', 'utm_campaign',
        'ip', 'user_agent', 'device', 'country', 'visited_date',
    ];

    protected $casts = ['visited_date' => 'date'];
}
