<?php

namespace App\Http\Resources\Backend\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseCategoryContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->when($this->id, $this->id),
            'name' => $this->name,
            'language_id' =>$this->when($this->language_id,$this->language_id)
        ];
    }
}
