<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileMealSchedule extends Model
{
    protected $fillable = [
        'profile_id',
        'meal_schedule_id',
    ];

    /**
     * Get the profile that owns the meal schedule.
     *
     * @return BelongsTo
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    /**
     * Get the meal schedule that the profile owns.
     *
     * @return BelongsTo
     */
    public function mealSchedule(): BelongsTo
    {
        return $this->belongsTo(MealSchedule::class);
    }
}
