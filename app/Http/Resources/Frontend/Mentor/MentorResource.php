<?php

namespace App\Http\Resources\Frontend\Mentor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MentorResource extends JsonResource
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
            'image' => asset($this->image),
            'is_head' => $this->is_head,
            'department' => $this->whenLoaded('courseCategory', $this->courseCategory?->content?->name),
            'profileContent' => $this->whenLoaded('profileContent', fn() => new MentorProfileContentResource($this->profileContent)),
        ];
    }
}
