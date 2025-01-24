<?php

namespace App\Http\Resources\Frontend\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseListResource extends JsonResource
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
            'thumbnail' => $this->video_thumbnail ? asset($this->video_thumbnail) : null,
            'icon' => (request()->course_status ==  2 ?  asset($this->online_icon) : asset($this->offline_icon)),
            'price' => (request()->course_status == 2 ?  $this->online_price : $this->offline_price),
            'status' => $this->when($this->status, $this->status),
            'bg_color' => $this->when($this->bg_color, $this->bg_color),
            'btn_color' => $this->when($this->btn_color, $this->btn_color),
            'content' => $this->whenLoaded('content', fn() => new CourseContentResource($this->content)),
        ];
    }
}
