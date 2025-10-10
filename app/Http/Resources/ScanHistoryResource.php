<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScanHistoryResource extends JsonResource
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
            'profile_id' => $this->profile_id,
            'scanned_at' => $this->scanned_at,
            'property_id' => $this->property_id,
            'meal_schedule' => $this->meal_schedule,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Include profile relationship
            'profile' => [
                'id' => $this->profile?->id,
                'first_name' => $this->profile?->first_name,
                'middle_name' => $this->profile?->middle_name,
                'last_name' => $this->profile?->last_name,
                'unique_identifier' => $this->profile?->unique_identifier,
                'position' => $this->profile?->position,
                'department' => [
                    'id' => $this->profile?->department?->id,
                    'name' => $this->profile?->department?->name,
                    'code' => $this->profile?->department?->code,
                ],
            ],
            // Include property relationship
            'property' => [
                'id' => $this->property?->id,
                'name' => $this->property?->name,
                'code' => $this->property?->code,
            ],
        ];
    }
}
