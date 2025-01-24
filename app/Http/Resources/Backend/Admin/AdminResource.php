<?php

namespace App\Http\Resources\Backend\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'role_id' => $this->role_id,
            'role' => $this->role->name,
            'employee_id' => $this->employee_id,
            'name' => $this->name,
            'number' => $this->number,
            'email' => $this->email,
            'image' => $this->image ? asset($this->image) : null,
            'created_at' => $this->when($this->created_at, $this->created_at->format('Y-m-d h:i:s A')),
            'is_active' => $this->is_active,
        ];
    }
}
