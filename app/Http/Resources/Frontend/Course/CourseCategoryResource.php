<?php

namespace App\Http\Resources\Frontend\Course;

use App\Http\Resources\Backend\Course\CourseCategoryContentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseCategoryResource extends JsonResource
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
            'icon' => asset($this->icon),
            'slug' => $this->slug,
            'content' => $this->whenLoaded('content', fn() =>  new CourseCategoryContentResource($this->content)),
        ];
    }
}
