<?php

namespace App\Models\Course;

use App\Models\Admin;
use App\Models\Course\CourseCategoryContent;
use App\Models\Settings\Language;
use App\Models\SuccessStory\SuccessStory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CourseCategory extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean'];

    function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(CourseCategoryContent::class, 'course_category_id');
    }

    public function content(): HasOne
    {
        return $this->hasOne(CourseCategoryContent::class, 'course_category_id', 'id');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'course_category_id');
    }

    public function successStories(): HasMany
    {
        return $this->hasMany(SuccessStory::class, 'course_category_id');
    }
}
