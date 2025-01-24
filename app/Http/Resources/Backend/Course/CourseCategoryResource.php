<?php

namespace App\Http\Resources\Backend\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CreatedByResource;


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
            'icon' => $this->when($this->icon, asset($this->icon)),
            'slug' => $this->when($this->slug, $this->slug),
            'higher_key' => $this->when($this->higher_key, $this->higher_key),
            'is_active' => $this->when(isset($this->is_active), $this->is_active),
            'createdBy' => $this->whenLoaded('createdBy', fn() => new CreatedByResource($this->createdBy)),
            'created_at' => $this->when($this->created_at, fn() => $this->created_at->format('Y-m-d h:i:s A')),
            'content' => $this->whenLoaded('content', fn() =>  new CourseCategoryContentResource($this->content)),
            'contents' => $this->whenLoaded('contents', fn() =>  CourseCategoryContentResource::collection($this->contents)),
        ];
    }
}
