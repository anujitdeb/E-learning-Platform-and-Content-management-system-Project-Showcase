<?php

namespace App\Http\Resources\Frontend\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->when($this->name, $this->name),
            'course_duration' => $this->when($this->course_duration, $this->course_duration),
            'lectures_qty' => $this->when($this->lectures_qty, $this->lectures_qty),
            'project_qty' => $this->when($this->project_qty, $this->project_qty),
            'description' => $this->when($this->description, $this->description),
            'seminar_thumbnail' => $this->when($this->seminar_thumbnail, asset($this->seminar_thumbnail)),
        ];
    }
}
