<?php

namespace App\Http\Resources\ContentManagement;

use App\Http\Resources\CreatedByResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'language_id' => $this->language_id,
        ];
    }
}
