<?php

namespace App\Http\Resources\Frontend\Apps;
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
            'content' => $this->whenLoaded('content', fn() => new ContactContentResource($this->content)),
        ];
    }
}
