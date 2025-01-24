<?php

namespace App\Http\Resources\Frontend\Seminar;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeminarDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'course_category_id' => $this->when($this->course_category_id, $this->course_category_id),
            'video_id' => $this->when($this->video_id, $this->video_id),
            'image' => $this->thumbnail ? asset($this->thumbnail) : null,
            'topics' => $this->detailContent ? $this->detailContent->contents : [],
        ];
    }
}
