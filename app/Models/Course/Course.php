<?php

namespace App\Models\Course;

use App\Models\Admin;
use App\Models\Facility;
use App\Models\Seminar\Seminar;
use App\Models\SuccessStory\SuccessStory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = ['is_active' => 'boolean'];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    public function content(): HasOne
    {
        return $this->hasOne(CourseContent::class, 'course_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(CourseContent::class, 'course_id');
    }

    public function curriculums(): HasMany
    {
        return $this->hasMany(CourseCurriculum::class, 'course_id');
    }

    public function softwares(): BelongsToMany
    {
        return $this->belongsToMany(Software::class, 'course_software', 'course_id', 'software_id');
    }

    public function courseFacilities(): HasMany
    {
        return $this->hasMany(CourseFacility::class, 'course_id');
    }

    public function successStories(): HasMany
    {
        return $this->hasMany(SuccessStory::class, 'course_id');
    }

    public function seminars(): HasMany
    {
        return $this->hasMany(Seminar::class, 'course_id');
    }

    public function scopeDataFilter(Builder $query, array $data): Builder
    {
        return $query->where(function ($q) use ($data) {
            if (!empty($data['course_category_id'])) {
                $q->where('course_category_id', $data['course_category_id']);
            }

            if (!empty($data['course_name'])) {
                $q->whereHas('content', function ($query) use ($data) {
                    $query->where('name', 'like', '%' . $data['course_name'] . '%');
                });
            }

            if (!empty($data['status'])) {
                $q->where('status', $data['status']);
            }
        });
    }
}
