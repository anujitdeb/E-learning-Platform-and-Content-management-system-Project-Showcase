<?php

namespace App\Models\Authorization;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    function admins(): HasMany
    {
        return $this->hasMany(Admin::class, 'role_id');
    }

    function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    function scopeDataSearch($query, $data)
    {
        $query->where(function ($q) use ($data) {
            if (!empty($data['search_data'])) {
                $q->where('name', 'like', '%' . $data['search_data'] . '%');
            }
            if (!empty($data['is_active'])) {
                $q->where('is_active', $data['is_active'] == 1 ? true : false);
            }
        });
    }
}
