<?php

namespace App\Http\Resources\Frontend\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleCourseResource extends JsonResource
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
            'slug' => $this->slug,
            'video_thumbnail' => asset($this->video_thumbnail),
            'video_id' => $this->video_id,
            'offline_price' => $this->offline_price,
            'online_price' => $this->online_price,
            'content' => $this->whenLoaded('content', fn() => new CourseContentResource($this->content)),
        ];
    }
}
