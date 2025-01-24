<?php

namespace App\Models\ContentManagement;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class About extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = ['is_active' => 'boolean'];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function content(): HasOne
    {
        return $this->hasOne(AboutContent::class, 'about_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(AboutContent::class, 'about_id');
    }
}
