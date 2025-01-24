<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Authorization\Role;
use App\Models\Seminar\BookSeminar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'device_token'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hasPermission($permission): bool
    {
        $rolePermission = $this->role->permissions()->where('slug', $permission)->exists();
        if ($rolePermission) {
            return true;
        } else {
            return response()->json(["error" => "Unauthorized :("], 403);
        }
    }

    function check($permission)
    {
        return $this->role->permissions()->where('slug', $permission)->exists();
    }

    public function bookSeminar(): HasMany
    {
        return $this->hasMany(BookSeminar::class);
    }
}
