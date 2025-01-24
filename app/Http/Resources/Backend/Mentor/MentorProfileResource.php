<?php

namespace App\Http\Resources\Backend\Mentor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MentorProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'language_id' => $this->language_id,
            'name' => $this->name,
            'designation' => $this->designation,
            'experience' => $this->experience,
            'student_qty' => $this->student_qty,
        ];
    }
}
