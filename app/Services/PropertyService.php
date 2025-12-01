<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Interfaces\PropertyInterface;
use App\Models\Property;
use App\Models\MealSchedule;
use App\Models\MealScheduleItem;
use App\Models\PropertyMealSchedule;
use App\Traits\HttpErrorCodeTrait;
use App\Traits\ReturnModelCollectionTrait;
use App\Traits\ReturnModelTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

                // Create meal schedule if provided, otherwise create default
                if (isset($data['schedule']) && !empty($data['schedule'])) {
                    $this->createMealSchedule($property, $data['schedule']);
                } else {
                    // Create default meal schedule
                    $this->createDefaultMealSchedule($property);
                }

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
                $property = Property::findOrFail($propertyId);

                $property = tap($property)->update([
                    'name' => $data['name'] ?? $property->name,
                    'code' => $data['code'] ?? $property->code,
                    'description' => $data['description'] ?? $property->description
                ]);

                if (isset($data['schedule']) && !empty($data['schedule'])) {
                    $this->updateMealSchedule($property, $data['schedule']);
                }

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


    private function updateMealSchedule(Property $property, array $scheduleData): void
    {
        $userId = Auth::id() ?? 1;
        
        $propertyMealSchedule = PropertyMealSchedule::where('property_id', $property->id)->first();

        if ($propertyMealSchedule && $propertyMealSchedule->mealSchedule) {
            $mealSchedule = $propertyMealSchedule->mealSchedule;
            
            $mealSchedule->update([
                'updated_by' => $userId,
            ]);

            $enabledDays = array_keys($scheduleData);
            
            MealScheduleItem::where('meal_schedule_id', $mealSchedule->id)
                ->whereNotIn('day_type', $enabledDays)
                ->delete();

            foreach ($scheduleData as $day => $meals) {
                foreach (['breakfast', 'lunch', 'dinner'] as $mealType) {
                    if (isset($meals["{$mealType}_start"]) && isset($meals["{$mealType}_end"])) {
                        MealScheduleItem::updateOrCreate(
                            [
                                'meal_schedule_id' => $mealSchedule->id,
                                'day_type' => $day,
                                'meal_type' => $mealType,
                            ],
                            [
                                'time_start' => $meals["{$mealType}_start"] . ':00',
                                'time_end' => $meals["{$mealType}_end"] . ':00',
                            ]
                        );
                    }
                }
            }
        }
    }


    private function createDefaultMealSchedule(Property $property): void
    {
        $userId = Auth::id() ?? 1;
        
        $scheduleName = $property->name . ' Meal Schedule';

        $mealSchedule = MealSchedule::create([
            'name' => $scheduleName,
            'remarks' => 'Default weekly schedule for ' . $property->name,
            'days' => Helper::MEAL_SCHEDULE_DAYS,
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);

        // Create default meal schedule items for each day
        foreach (Helper::MEAL_SCHEDULE_DAYS as $day) {
            MealScheduleItem::create([
                'meal_schedule_id' => $mealSchedule->id,
                'time_start' => '07:00:00',
                'time_end' => '10:00:00',
                'day_type' => $day,
                'meal_type' => Helper::MEAL_SCHEDULE_BREAKFAST,
            ]);
            MealScheduleItem::create([
                'meal_schedule_id' => $mealSchedule->id,
                'time_start' => '11:00:00',
                'time_end' => '14:00:00',
                'day_type' => $day,
                'meal_type' => Helper::MEAL_SCHEDULE_LUNCH,
            ]);
            MealScheduleItem::create([
                'meal_schedule_id' => $mealSchedule->id,
                'time_start' => '17:00:00',
                'time_end' => '20:00:00',
                'day_type' => $day,
                'meal_type' => Helper::MEAL_SCHEDULE_DINNER,
            ]);
        }

        PropertyMealSchedule::create([
            'property_id' => $property->id,
            'meal_schedule_id' => $mealSchedule->id,
        ]);
    }


    private function createMealSchedule(Property $property, array $scheduleData): void
    {
        $userId = Auth::id() ?? 1;
        
        $scheduleName = $property->name . ' Meal Schedule';

        $mealSchedule = MealSchedule::create([
            'name' => $scheduleName,
            'remarks' => 'Weekly schedule for ' . $property->name,
            'days' => Helper::MEAL_SCHEDULE_DAYS,
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);

        foreach ($scheduleData as $day => $meals) {
            foreach (['breakfast', 'lunch', 'dinner'] as $mealType) {
                if (isset($meals["{$mealType}_start"]) && isset($meals["{$mealType}_end"])) {
                    MealScheduleItem::create([
                        'meal_schedule_id' => $mealSchedule->id,
                        'time_start' => $meals["{$mealType}_start"] . ':00',
                        'time_end' => $meals["{$mealType}_end"] . ':00',
                        'day_type' => $day,
                        'meal_type' => $mealType,
                    ]);
                }
            }
        }

        PropertyMealSchedule::create([
            'property_id' => $property->id,
            'meal_schedule_id' => $mealSchedule->id,
        ]);
    }
}
