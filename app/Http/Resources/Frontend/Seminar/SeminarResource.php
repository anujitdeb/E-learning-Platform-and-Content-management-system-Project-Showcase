<?php

namespace App\Http\Resources\Frontend\Seminar;

use App\Http\Resources\Backend\Course\CourseResource;
use App\Http\Resources\Frontend\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeminarResource extends JsonResource
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
            'date' => $this->datetime->format('d F, Y'),
            'time' => $this->datetime->format('h:i A'),
            'name' => $this->content ? $this->content->name : null,
            'seminar_type' => $this->seminar_type,
            'type' => $this->type,
            'link' => $this->when($this->link, $this->link),
            'location' => $this->whenLoaded('location', fn() => new LocationResource($this->location)),
            'course' => $this->whenLoaded('course', fn() => new CourseResource($this->course)),
            'platform' => $this->when($this->platform, $this->platform),
            'detail' => $this->whenLoaded('seminarDetail', fn() => new SeminarDetailResource($this->seminarDetail)),
        ];
    }
}
