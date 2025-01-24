<?php

namespace App\Http\Resources\ContentManagement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'whats_app_link' => $this->whats_app_link,
            'messenger_link' => $this->messenger_link,
            'hotline_number' => $this->hotline_number,
            'icon' =>  asset($this->icon),
            'contents' => $this->whenLoaded('contents', fn() =>  ContactContentResource::collection($this->contents)),
        ];
    }
}
