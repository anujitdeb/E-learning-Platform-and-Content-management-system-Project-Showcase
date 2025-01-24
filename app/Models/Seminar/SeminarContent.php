<?php

namespace App\Models\Seminar;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeminarContent extends Model
{
    protected $guarded = ['id'];
    
    public function seminar(): BelongsTo
    {
        return $this->belongsTo(Seminar::class, 'seminar_id');
    }
}
