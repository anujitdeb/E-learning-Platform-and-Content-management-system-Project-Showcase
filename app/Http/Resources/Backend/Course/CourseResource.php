<?php

namespace App\Http\Resources\Backend\Course;

use App\Http\Resources\CreatedByResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'slug' => $this->when($this->slug, $this->slug),
            'offline_icon' => $this->when($this->offline_icon, asset($this->offline_icon)),
            'online_icon' => $this->when($this->online_icon, asset($this->online_icon)),
            'seminar_thumbnail' => $this->when($this->seminar_thumbnail, asset($this->seminar_thumbnail)),
            'video_thumbnail' => $this->when($this->video_thumbnail, asset($this->video_thumbnail)),
            'video_id' => $this->when($this->video_id, $this->video_id),
            'offline_price' => $this->when($this->offline_price, $this->offline_price),
            'online_price' => $this->when($this->online_price, $this->online_price),
            'bg_color' => $this->when($this->bg_color, $this->bg_color),
            'btn_color' => $this->when($this->btn_color, $this->btn_color),
            'status' => $this->when($this->status, $this->status),
            'is_active' => $this->when($this->is_active, $this->is_active),
            'created_at' => $this->when($this->created_at, $this->created_at),
            'createdBy' => $this->whenLoaded('createdBy', fn() => new CreatedByResource($this->createdBy)),
            'content' => $this->whenLoaded('content', fn() => new CourseContentResource($this->content)),
            'contents' => $this->whenLoaded('contents', fn() => CourseContentResource::collection($this->contents)),
            'category' => $this->whenLoaded('category', fn() => new CourseCategoryResource($this->category))
        ];
    }
}
