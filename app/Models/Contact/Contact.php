<?php

namespace App\Models\Contact;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contact extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['is_active' => 'boolean'];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
    public function content(): HasOne
    {
        return $this->hasOne(ContactContent::class, 'contact_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(ContactContent::class, 'contact_id');
    }
}
