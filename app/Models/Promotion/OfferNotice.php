<?php

namespace App\Models\Promotion;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferNotice extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'end_date' => 'date'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
