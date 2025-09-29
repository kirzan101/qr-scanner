<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MealScheduleItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'meal_schedule_id',
        'time',
        'meal_type',
    ];

    public function mealSchedule(): BelongsTo
    {
        return $this->belongsTo(MealSchedule::class, 'meal_schedule_id');
    }
}
