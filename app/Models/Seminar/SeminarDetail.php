<?php

namespace App\Models\Seminar;

use App\Models\Admin;
use App\Models\Course\Course;
use App\Models\Course\CourseCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SeminarDetail extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean'];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function detailContent(): HasOne
    {
        return $this->hasOne(SeminarDetailContent::class, 'seminar_detail_id');
    }

    public function detailContents(): HasMany
    {
        return $this->hasMany(SeminarDetailContent::class, 'seminar_detail_id');
    }

    public function courseCategory(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function seminars(): HasOne
    {
        return $this->hasOne(Seminar::class, 'seminar_detail_id');
    }

    public function scopeDataFilter(Builder $query, array $data): Builder
    {
        return $query->where(function ($q) use ($data) {
            if (!empty($data['course_id'])) {
                $q->where('course_id', $data['course_id']);
            }
            if (!empty($data['course_category_id'])) {
                $q->where('course_category_id', $data['course_category_id']);
            }
        });
    }
}
