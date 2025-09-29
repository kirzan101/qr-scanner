<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Interfaces\ProfileInterface;
use App\Models\Profile;
use App\Traits\HttpErrorCodeTrait;
use App\Traits\ReturnModelCollectionTrait;
use App\Traits\ReturnModelTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProfileService implements ProfileInterface
{
    use HttpErrorCodeTrait,
        ReturnModelCollectionTrait,
        ReturnModelTrait;

    public function __construct(
        private UserService $user
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function storeProfile(array $data): array
    {
        try {
            return DB::transaction(function () use ($data) {

                $userResult = $this->user->storeUser($data);

                if ($userResult['status'] === Helper::ERROR) {
                    throw ValidationException::withMessages([
                        'errors' =>  'Error on creation user: ' . $userResult['message']
                    ]);
                }

                $profile = Profile::create([
                    'first_name' => $data['first_name'] ?? null,
                    'middle_name' => $data['middle_name'] ?? null,
                    'last_name' => $data['last_name'] ?? null,
                    'unique_identifier' => $data['unique_identifier'] ?? null,
                    'position' => $data['position'] ?? null,
                    'property_id' => $data['property_id'] ?? 1,
                    'department_id' => $data['department_id'] ?? 1,
                    'user_id' => $userResult['last_id'] ?? null,
                ]);

                return $this->returnModel(201, 'success', 'Profile created successfully!', $profile, $profile->id);
            });
        } catch (\Throwable $th) {
            $code = $this->httpCode($th);
            return $this->returnModel($code, 'error', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateProfile($profileId, array $data): array
    {
        try {
            return DB::transaction(function () use ($profileId, $data) {
                $profile = Profile::findOrFail($profileId);

                $userResult = $this->user->updateUser($profile->user_id, $data);

                if ($userResult['status'] === Helper::ERROR) {
                    throw ValidationException::withMessages([
                        'errors' =>  'Error on creation user: ' . $userResult['message']
                    ]);
                }

                $profile = tap($profile)->update([
                    'first_name' => $data['first_name'] ?? $profile->first_name,
                    'middle_name' => $data['middle_name'] ?? $profile->middle_name,
                    'last_name' => $data['last_name'] ?? $profile->last_name,
                    'unique_identifier' => $data['unique_identifier'] ?? $profile->unique_identifier,
                    'property_id' => $data['property_id'] ?? $profile->property_id,
                    'department_id' => $data['department_id'] ?? $profile->department_id,
                    'position' => $data['position'] ?? $profile->position,
                ]);

                return $this->returnModel(200, 'success', 'Profile updated successfully!', $profile, $profile->id);
            });
        } catch (\Throwable $th) {
            $code = $this->httpCode($th);
            return $this->returnModel($code, 'error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteProfile($profileId): array
    {
        try {
            return DB::transaction(function () use ($profileId) {
                $profile = Profile::findOrFail($profileId);
                $profile->delete();

                return $this->returnModel(200, 'success', 'Profile deleted successfully!');
            });
        } catch (\Throwable $th) {
            $code = $this->httpCode($th);
            return $this->returnModel($code, 'error', $th->getMessage());
        }
    }
}
