<?php

namespace App\Http\Resources\ContentManagement;

use App\Http\Resources\CreatedByResource;
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
            'image' =>  asset($this->image),
            'is_active' => $this->when(isset($this->is_active), $this->is_active),
            'created_at' => $this->when($this->created_at, fn() => $this->created_at->format('Y-m-d h:i:s A')),
            'createdBy' => $this->whenLoaded('createdBy', fn() => new CreatedByResource($this->createdBy)),
            'contents' => $this->whenLoaded('contents', fn() =>  AboutContentResource::collection($this->contents)),
            'content' => $this->whenLoaded('content', fn() =>  new AboutContentResource($this->content)),
        ];
    }
}
