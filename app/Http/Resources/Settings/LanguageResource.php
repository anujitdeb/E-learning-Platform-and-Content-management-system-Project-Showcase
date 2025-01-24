<?php

namespace App\Http\Resources\Settings;

use App\Http\Resources\CreatedByResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguageResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->when($this->slug, $this->slug),
            'code' => $this->when($this->code, $this->code),
            'icon' => $this->icon ? asset($this->icon) : null,
            'createdBy' => $this->whenLoaded('createdBy', fn() => new CreatedByResource($this->createdBy)),
            'is_active' => $this->when(isset($this->is_active), $this->is_active),
            'created_at' => $this->when($this->created_at, fn() => $this->created_at->format('Y-m-d h:i:s A')),
        ];
    }
}
