<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Interfaces\ScanHistoryInterface;
use App\Interfaces\ScanProcessInterface;
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
                $scanData = [
                    'profile_id' => $data['profile_id'],
                    'scanned_at' => Carbon::now(),
                    'property_id' => $data['property_id'],
                    'meal_schedule' => $data['meal_schedule'] // e.g., breakfast, lunch, dinner
                ];

                $scanResult = $this->scanHistory->storeScanHistory($scanData);
                $this->ensureSuccess($scanResult, 'Failed to scan.');

                return $this->returnModel(201, Helper::SUCCESS, 'Successfully scanned!');
            });
        } catch (\Throwable $th) {
            $code = $this->httpCode($th);
            return $this->returnModel($code, Helper::ERROR, $th->getMessage());
        }
    }
}
