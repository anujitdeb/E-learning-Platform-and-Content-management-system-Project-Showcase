<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->when($this->email, $this->email),
            'student_id' => $this->when($this->student_id, fn() => $this->student_id),
            'admission_id' => $this->when($this->admission_id, fn() => $this->admission_id),
            'number' => $this->when($this->number, $this->number),
            'is_number' => $this->when($this->is_number, fn() => $this->is_number),
            'location' => $this->when($this->location, $this->location),
            'image' => $this->image ? asset($this->image) : "https://placehold.co/300x300",
            'is_complete' => $this->when($this->is_complete, $this->is_complete),
            'is_admitted' => $this->when($this->is_admitted, $this->is_admitted),
        ];
    }
}
