<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Interfaces\PropertyInterface;
use App\Models\Property;
use App\Traits\HttpErrorCodeTrait;
use App\Traits\ReturnModelCollectionTrait;
use App\Traits\ReturnModelTrait;
use Illuminate\Support\Facades\DB;

class PropertyService implements PropertyInterface
{
    use HttpErrorCodeTrait,
        ReturnModelCollectionTrait,
        ReturnModelTrait;

    /**
     * Store a newly created property in storage.
     */
    public function storeProperty(array $data): array
    {
        try {
            return DB::transaction(function () use ($data) {
                $property = Property::create([
                    'name' => $data['name'],
                    'code' => $data['code'],
                    'description' => $data['description']
                ]);

                return $this->returnModel(201, Helper::SUCCESS, 'Property created successfully!', $property, $property->id);
            });
        } catch (\Exception $e) {
            $code = $this->httpCode($e);
            return $this->returnModel($code, Helper::ERROR, $e->getMessage());
        }
    }

    /**
     * Update the specified property in storage.
     */
    public function updateProperty($propertyId, array $data): array
    {
        try {
            return DB::transaction(function () use ($propertyId, $data) {
                $property = Property::create([
                    'name' => $data['name'] ?? null,
                    'code' => $data['code'] ?? null,
                    'description' => $data['description'] ?? null

                ]);

                return $this->returnModel(200, Helper::SUCCESS, 'Property updated successfully!', $property, $property->id);
            });
        } catch (\Exception $e) {
            $code = $this->httpCode($e);
            return $this->returnModel($code, Helper::ERROR, $e->getMessage());
        }
    }

    /**
     * Remove the specified property from storage.
     */
    public function deleteProperty($propertyId): array
    {
        try {
            return DB::transaction(function () use ($propertyId) {
                $property = Property::findOrFail($propertyId);
                $property->delete();

                return $this->returnModel(200, Helper::SUCCESS, 'Property deleted successfully!');
            });
        } catch (\Exception $e) {
            $code = $this->httpCode($e);
            return $this->returnModel($code, Helper::ERROR, $e->getMessage());
        }
    }
}
