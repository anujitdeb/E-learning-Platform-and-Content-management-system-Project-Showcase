<?php

namespace App\Models\Seminar;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeminarDetailContent extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['contents' => 'array'];

    public function seminarDetail(): BelongsTo
    {
        return $this->belongsTo(SeminarDetail::class, 'seminar_detail_id');
    }
}
