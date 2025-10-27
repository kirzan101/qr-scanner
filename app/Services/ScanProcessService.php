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

                if (!$profile) {
                    return $this->returnModel(201, Helper::SUCCESS, 'Unidentified qr code');
                }

                // Check if the same profile was scanned within the last hour
                $oneHourAgo = Carbon::now()->subHour();
                $recentScan = DB::table('scan_histories')
                    ->where('profile_id', $profile->id)
                    ->where('scanned_at', '>=', $oneHourAgo)
                    ->orderBy('scanned_at', 'desc')
                    ->first();

                if ($recentScan) {
                    $timeDiff = Carbon::parse($recentScan->scanned_at)->diffForHumans(Carbon::now(), true);
                    $lastScanTime = Carbon::parse($recentScan->scanned_at)->format('h:i A');

                    return $this->returnModel(
                        201,
                        Helper::SUCCESS,
                        "Already scanned! This QR code was scanned {$timeDiff} ago at {$lastScanTime}. Please wait at least 1 hour between scans."
                    );
                }

                // If no recent scan found, proceed with scanning
                $scanData = [
                    'profile_id' => $profile->id,
                    'scanned_at' => Carbon::now(),
                    'property_id' => $profile->property_id,
                    'meal_schedule' => 'Lunch' // e.g., breakfast, lunch, dinner
                ];

                $scanResult = $this->scanHistory->storeScanHistory($scanData);

                $this->ensureSuccess($scanResult, 'Failed to scan.');

                return $this->returnModel(
                    201,
                    Helper::SUCCESS,
                    "Successfully scanned! Welcome, {$profile->first_name} {$profile->last_name}.",
                    $profile,
                    $profile->id
                );
            });
        } catch (\Throwable $th) {
            $code = $this->httpCode($th);
            return $this->returnModel($code, Helper::ERROR, $th->getMessage());
        }
    }
}
