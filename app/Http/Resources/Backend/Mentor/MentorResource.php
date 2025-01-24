<?php

namespace App\Http\Resources\Backend\Mentor;

use App\Http\Resources\Backend\Course\CourseCategoryContentResource;
use App\Http\Resources\Backend\Course\CourseCategoryResource;
use App\Http\Resources\CreatedByResource;
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
            'course_category_id' => $this->course_category_id,
            'is_active' => $this->is_active,
            'is_head' => $this->is_head,
            'name' => $this->profileContent?->name,
            'designation' => $this->profileContent?->designation,
            'createdBy' => $this->whenLoaded('createdBy', fn() => new CreatedByResource($this->createdBy)),
            'created_at' => $this->when($this->created_at, fn() => $this->created_at->format('Y-m-d h:i:s A')),
            'courseCategoryContent' => $this->whenLoaded('courseCategoryContent', fn() => new CourseCategoryContentResource($this->courseCategoryContent)),
            'contents' => $this->whenLoaded('contents', fn() =>  MentorProfileResource::collection($this->contents)),
        ];
    }
}
