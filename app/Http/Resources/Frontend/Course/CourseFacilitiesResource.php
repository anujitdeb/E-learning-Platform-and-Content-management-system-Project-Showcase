<?php

namespace App\Http\Resources\Frontend\Course;

use App\Http\Resources\Frontend\ContentManagement\FacilityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseFacilitiesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'facility' => $this->whenLoaded('facility', new FacilityResource($this->facility)),
        ];
    }
}
