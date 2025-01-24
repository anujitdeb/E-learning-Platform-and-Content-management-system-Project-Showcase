<?php

namespace App\Models;

use App\Models\Authorization\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = ['id'];
    
    protected $casts = ['is_active' => 'boolean'];

    function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    function check($permission)
    {
        return $this->role->permissions()->where('slug', $permission)->exists();
    }

    function scopeDataFilter(Builder $query, array $data): Builder
    {
        return $query->where(function ($q) use ($data) {
            if (!empty($data['search_data'])) {
                $q->where('name', 'like', '%' . $data['search_data'] . '%');
                $q->orWhere('email', 'like', '%' . $data['search_data'] . '%');
                $q->orWhere('number', 'like', '%' . $data['search_data'] . '%');
                $q->orWhere('employee_id', 'like', '%' . $data['search_data'] . '%');
            }
        });
    }
}
