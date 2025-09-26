<?php

namespace App\Http\Resources;

use App\Traits\ReturnDatetimeFormat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScanProfileResource extends JsonResource
{
    use ReturnDatetimeFormat;

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
            'username' => $this->user->username,
            'full_name' => $this->getFullName(),
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'last_login_at' => $this->user->last_login_at,
            'is_able_to_login' => (bool)$this->user->is_able_to_login,
            'email' => $this->user->email,
            'unique_identifier' => $this->unique_identifier,
            'position' => $this->position,
            'property_id' => $this->property_id,
            'created_at' => $this->returnShortDateTime($this->created_at),
            'updated_at' => $this->returnShortDateTime($this->updated_at),
        ];
    }
}
