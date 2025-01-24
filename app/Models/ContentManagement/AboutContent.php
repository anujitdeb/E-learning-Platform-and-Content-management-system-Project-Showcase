<?php

namespace App\Models\ContentManagement;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AboutContent extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = ['is_active' => 'boolean'];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function about(): BelongsTo
    {
        return $this->belongsTo(About::class, 'about_id');
    }
}
