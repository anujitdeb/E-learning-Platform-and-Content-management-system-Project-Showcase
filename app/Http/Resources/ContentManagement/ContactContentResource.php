<?php

namespace App\Http\Resources\ContentManagement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'language_id' => $this->language_id,
            'title' => $this->title,
            'description' => $this->description,
            'btn_name' => $this->btn_name,
        ];
    }
}
