<?php

namespace App\Http\Resources\Frontend\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentWiseCoursesResource extends JsonResource
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
            'department' => $this->whenLoaded('content', $this->content->name),
            'slug' => $this->slug,
            'courses' => $this->whenLoaded('courses', fn() => CourseListResource::collection($this->courses)),
        ];
    }
}
