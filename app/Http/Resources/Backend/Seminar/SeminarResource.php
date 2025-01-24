<?php

namespace App\Http\Resources\Backend\Seminar;

use App\Http\Resources\Backend\Course\CourseResource;
use App\Http\Resources\CreatedByResource;
use App\Http\Resources\Settings\LocationResource;
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
            'link' => $this->link,
            'type' => $this->type,
            'datetime' => $this->datetime,
            'platform' => $this->platform,
            'is_active' => $this->is_active,
            'course_id' => $this->course_id,
            'location_id' => $this->location_id,
            'seminar_type' => $this->seminar_type,
            'seminar_detail_id' => $this->seminar_detail_id,
            'course' => $this->whenLoaded('course', fn() => new CourseResource($this->course)),
            'location' => $this->whenLoaded('location', fn() => new LocationResource($this->location)),
            'content' => $this->whenLoaded('content', fn() => new SeminarContentResource($this->content)),
            'createdBy' => $this->whenLoaded('createdBy', fn() => new CreatedByResource($this->createdBy)),
            'created_at' => $this->when($this->created_at, $this->created_at->format('Y-m-d h:i:s A')),
            'contents' => $this->whenLoaded('contents', fn() => SeminarContentResource::collection($this->contents)),
        ];
    }
}
