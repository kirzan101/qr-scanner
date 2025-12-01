<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Interfaces\ScanHistoryInterface;
use App\Interfaces\ScanProcessInterface;
use App\Models\MealSchedule;
use App\Models\MealScheduleItem;
use App\Models\Profile;
use App\Models\PropertyMealSchedule;
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

                // Determine meal type based on property's meal schedule
                $mealType = $this->determineMealType($profile->property_id);

                // If no recent scan found, proceed with scanning
                $scanData = [
                    'profile_id' => $profile->id,
                    'scanned_at' => Carbon::now(),
                    'property_id' => $profile->property_id,
                    'meal_schedule' => $mealType
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

    
    private function determineMealType(?int $propertyId): string
    {
        if (!$propertyId) {
            return 'Unknown';
        }

        try {
            $now = Carbon::now('Asia/Manila');
            $currentTime = $now->format('H:i:s');
            $currentDay = $now->format('l'); 

            $propertyMealSchedule = PropertyMealSchedule::where('property_id', $propertyId)
                ->with('mealSchedule.mealSchedule')
                ->first();

            if (!$propertyMealSchedule || !$propertyMealSchedule->mealSchedule) {
                return 'No Schedule';
            }

            $mealScheduleItems = MealScheduleItem::where('meal_schedule_id', $propertyMealSchedule->meal_schedule_id)
                ->where('day_type', $currentDay)
                ->get();

            if ($mealScheduleItems->isEmpty()) {
                return 'Day Not Available';
            }

            $mealTypes = ['breakfast', 'lunch', 'dinner'];
            $lastMeal = null;

            foreach ($mealTypes as $mealType) {
                $item = $mealScheduleItems->firstWhere('meal_type', $mealType);
                
                if ($item) {
                    $lastMeal = $item;
                    
                    // Check if current time is within this meal period
                    if ($currentTime >= $item->time_start && $currentTime <= $item->time_end) {
                        return ucfirst($mealType);
                    }
                    
                    // Check if current time is after this meal's end but before the next meal
                    if ($currentTime > $item->time_end) {
                        // Continue to check if it's within the next meal period
                        continue;
                    }
                    
                    // If current time is before this meal starts
                    if ($currentTime < $item->time_start) {
                        // Check if it's late from the previous meal
                        $prevMealIndex = array_search($mealType, $mealTypes) - 1;
                        if ($prevMealIndex >= 0) {
                            $prevMeal = $mealScheduleItems->firstWhere('meal_type', $mealTypes[$prevMealIndex]);
                            if ($prevMeal && $currentTime > $prevMeal->time_end) {
                                return ucfirst($mealTypes[$prevMealIndex]) . '-Late';
                            }
                        }
                        // If it's before the first meal, it might be very early or very late from dinner
                        return 'Early/Before Schedule';
                    }
                }
            }

            // If we've gone through all meals and current time is after the last meal
            if ($lastMeal && $currentTime > $lastMeal->time_end) {
                return ucfirst($lastMeal->meal_type) . '-Late';
            }

            return 'Unknown';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
