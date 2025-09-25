<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScanProfileResource extends JsonResource
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
            'user_id' => $this->user_id,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'unique_identifier' => $this->unique_identifier,
            'position' => $this->position,
        ];
    }
}
