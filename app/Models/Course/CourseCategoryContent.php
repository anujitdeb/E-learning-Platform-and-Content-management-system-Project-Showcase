<?php

namespace App\Models\Course;

use App\Models\Settings\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseCategoryContent extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean'];

    function courseCategory(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
