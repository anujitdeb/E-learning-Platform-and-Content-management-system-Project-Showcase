<?php

namespace App\Http\Resources\Frontend\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseSuccessStoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'thumbnail' => asset($this->thumbnail),
            'vedio_id' => $this->vedio_id
        ];
    }
}
