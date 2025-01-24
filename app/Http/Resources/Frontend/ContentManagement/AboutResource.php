<?php

namespace App\Http\Resources\Frontend\ContentManagement;

use App\Http\Resources\Frontend\ContentManagement\AboutContentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutResource extends JsonResource
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
            'image' => $this->when($this->image, asset($this->image)),
            'content' => $this->whenLoaded('content', fn() => new AboutContentResource($this->content)),
        ];
    }
}
