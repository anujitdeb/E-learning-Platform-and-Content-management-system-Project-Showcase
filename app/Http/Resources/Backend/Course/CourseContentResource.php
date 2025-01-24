<?php

namespace App\Http\Resources\Backend\Course;

use App\Http\Resources\CreatedByResource;
use App\Http\Resources\Frontend\LanguageResource;
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
            'id' => $this->id,
            'language' => $this->whenLoaded('language', fn() => new LanguageResource($this->language)),
            'name' => $this->when($this->name, $this->name),
            'course_id' => $this->when($this->course_id, $this->course_id),
            'language_id' => $this->when($this->language_id, $this->language_id),
            'course_duration' => $this->when($this->course_duration, $this->course_duration),
            'lectures_qty' => $this->when($this->lectures_qty, $this->lectures_qty),
            'project_qty' => $this->when($this->project_qty, $this->project_qty),
            'description' => $this->when($this->description, $this->description),
        ];
    }
}
