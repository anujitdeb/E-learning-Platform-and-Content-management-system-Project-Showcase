<?php

namespace App\Models\Seminar;

use App\Models\Admin;
use App\Models\Course\Course;
use App\Models\Settings\Location;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Seminar extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['datetime' => 'datetime', 'is_active' => 'boolean'];

    public function bookSeminar(): HasMany
    {
        return $this->hasMany(BookSeminar::class, 'seminar_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function content(): HasOne
    {
        return $this->hasOne(SeminarContent::class, 'seminar_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(SeminarContent::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function seminarDetail(): BelongsTo
    {
        return $this->belongsTo(SeminarDetail::class, 'seminar_detail_id');
    }

    public function scopeDataFilter(Builder $query, array $data): Builder
    {
        return $query->where(function ($q) use ($data) {
            if (!empty($data['location_id'])) {
                if ($data['location_id'] == 'online') {
                    $q->whereNull('location_id');
                } else {
                    $q->where('location_id', $data['location_id']);
                }
            }

            if (!empty($data['course_id'])) {
                $q->where('course_id', $data['course_id']);
            }

            if (!empty($data['type'])) {
                $q->where('type', $data['type']);
            }

            if (!empty($data['seminar_type'])) {
                $q->where('seminar_type', $data['seminar_type']);
            }

        });
    }
}
