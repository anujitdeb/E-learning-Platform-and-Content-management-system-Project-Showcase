<?php

namespace App\Http\Resources\Backend\Promotion;

use App\Http\Resources\CreatedByResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferNoticeResource extends JsonResource
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
            'createdBy' => $this->whenLoaded('createdBy', fn() => new CreatedByResource($this->createdBy)),
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image ? asset($this->image) : null,
            'thumbnail' => $this->thumbnail ? asset($this->thumbnail) : null,
            'status' => $this->when($this->status, $this->status),
            'end_date' => $this->when($this->end_date, $this->end_date->format('Y-m-d')),
            'created_at' => $this->when($this->created_at, $this->created_at->diffForHumans()),
        ];
    }
}
