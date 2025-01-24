<?php

namespace App\Http\Resources\Frontend\Mentor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MentorProfileContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'designation' => $this->designation,
            'experience' => $this->experience,
            'student_qty' => $this->student_qty,
        ];
    }
}
