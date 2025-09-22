<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Helper
{
    /**
     * set default success status
     */
    const SUCCESS = 'success';

    /**
     * set default error status
     */
    const ERROR = 'error';

    /**
     * Action types for user permissions.
     */
    const ACTION_TYPES = [
        'create',
        'update',
        'delete',
        'view',
    ];
    const ACTION_TYPE_CREATE = 'create';
    const ACTION_TYPE_UPDATE = 'update';
    const ACTION_TYPE_DELETE = 'delete';
    const ACTION_TYPE_VIEW = 'view';

    /**
     * account statuses
     */
    const ACCOUNT_STATUSES = [
        'active',
        'inactive'
    ];
    const ACCOUNT_STATUS_ACTIVE = 'active';
    const ACCOUNT_STATUS_INACTIVE = 'inactive';

    /**
     * Meal Schedules
     */
    const MEAL_SCHEDULES = [
        'breakfast',
        'lunch',
        'dinner',
    ];
    const MEAL_SCHEDULE_BREAKFAST = 'breakfast';
    const MEAL_SCHEDULE_LUNCH = 'lunch';
    const MEAL_SCHEDULE_DINNER = 'dinner';

    /**
     * Profile Positions
     */
    const PROFILE_POSITIONS = [
        'Employee',
        'Manager',
        'OJT'
    ];
    const PROFILE_POSITION_EMPLOYEE = 'Employee';
    const PROFILE_POSITION_MANAGER = 'Manager';
    const PROFILE_POSITION_OJT = 'OJT';
}
