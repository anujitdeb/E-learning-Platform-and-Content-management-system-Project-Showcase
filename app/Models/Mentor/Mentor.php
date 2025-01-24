<?php

namespace App\Models\Mentor;

use App\Models\Admin;
use App\Models\Course\CourseCategory;
use App\Models\Course\CourseCategoryContent;
use App\Models\Course\Marketplace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mentor extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean', 'is_head' => 'boolean'];


    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
    public function courseCategory(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }
    public function courseCategoryContent(): HasOne
    {
        return $this->hasOne(CourseCategoryContent::class, 'course_category_id', 'course_category_id');
    }
    public function profileContent(): HasOne
    {
        return $this->hasOne(MentorProfile::class, 'mentor_id');
    }
    public function contents(): HasMany
    {
        return $this->hasMany(MentorProfile::class, 'mentor_id');
    }

    public function marketplaces(): BelongsToMany
    {
        return $this->belongsToMany(Marketplace::class);
    }
    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class, 'mentor_id');
    }

    public function scopeDataFilter(Builder $query, $data): Builder
    {
        return  $query->where(function ($q) use ($data) {
            if (!empty($data['course_category_id'])) {
                $q->where('course_category_id', $data['course_category_id']);
            }
            if (!empty($data['name'])) {
                $q->whereHas('profileContent', function ($q) use ($data) {
                    $q->where('name', 'like', '%' . $data['name'] . '%');
                });
            }
        });
    }
}
