<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsClick extends Model
{
    protected $fillable = ['cs_agent_id', 'ip', 'page', 'device'];

    public function agent()
    {
        return $this->belongsTo(CsAgent::class);
    }
}
