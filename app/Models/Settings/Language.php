<?php

namespace App\Models\Settings;

use App\Models\Admin;
use App\Models\Course\CourseContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean'];

    function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    function courseContent(): HasOne
    {
        return $this->hasOne(CourseContent::class, 'language_id');
    }
}
