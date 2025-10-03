<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Interfaces\ScanHistoryInterface;
use App\Interfaces\ScanProcessInterface;
use App\Models\Profile;
use App\Traits\EnsureSuccessTrait;
use App\Traits\HttpErrorCodeTrait;
use App\Traits\ReturnModelCollectionTrait;
use App\Traits\ReturnModelTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScanProcessService implements ScanProcessInterface
{
    use HttpErrorCodeTrait,
        ReturnModelCollectionTrait,
        ReturnModelTrait,
        EnsureSuccessTrait;

    public function __construct(
        private ScanHistoryInterface $scanHistory
    ) {}

    /**
     * Process a scan action.
     */
    public function processScan(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {

                $profile = Profile::where('unique_identifier', $data['unique_identifier'])->first();


                if ($profile) {
                    $scanData = [
                        'profile_id' => $profile->id,
                        'scanned_at' => Carbon::now(),
                        'property_id' => $profile->property_id,
                        'meal_schedule' => 'Lunch' // e.g., breakfast, lunch, dinner
                    ];

                    $scanResult = $this->scanHistory->storeScanHistory($scanData);

                    $this->ensureSuccess($scanResult, 'Failed to scan.');
                    return $this->returnModel(201, Helper::SUCCESS, 'Successfully scanned!', $profile, $profile->id);
                }

                return $this->returnModel(200, Helper::SUCCESS, 'Profile not found');
            });
        } catch (\Throwable $th) {
            $code = $this->httpCode($th);
            return $this->returnModel($code, Helper::ERROR, $th->getMessage());
        }
    }
}
