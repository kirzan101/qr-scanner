<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Models\MealSchedule;
use App\Models\MealScheduleItem;
use App\Models\Profile;
use App\Models\ProfileMealSchedule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileMealSchedulePerPropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles =
            [
                [
                    'username' => 'astoriaplaza',
                    'email' => 'astoriaplaza@example.com',
                    'is_able_to_login' => 1,
                    'first_name' => 'Astoria',
                    'last_name' => 'Plaza',
                    'unique_identifier' => 1,
                    'position' => '',
                    'department_id' => 40,
                    'property_id' => 1,
                    'location_id' => 1,
                ],
                [
                    'username' => 'astoriacurrent',
                    'email' => 'astoriacurrent@example.com',
                    'is_able_to_login' => 1,
                    'first_name' => 'Astoria',
                    'last_name' => 'Current',
                    'unique_identifier' => 2,
                    'position' => '',
                    'department_id' => 40,
                    'property_id' => 2,
                    'location_id' => 2,
                ],
            ];

        foreach ($profiles as $profile) {
            $user = User::create([
                'username' => $profile['username'],
                'email' => $profile['email'],
                'password' => bcrypt($profile['username']),
                'isAdmin' => false,
                'is_able_to_login' => true
            ]);

            $profile = Profile::create([
                'first_name' => $profile['first_name'] ?? null,
                'middle_name' => $profile['middle_name'] ?? null,
                'last_name' => $profile['last_name'] ?? null,
                'unique_identifier' => $profile['unique_identifier'] ?? null,
                'position' => $profile['position'] ?? null,
                'property_id' => $profile['property_id'] ?? 1,
                'department_id' => $profile['department_id'] ?? 1,
                'location_id' => $profile['location_id'] ?? 1,
                'user_id' => $user->id ?? null,
            ]);



            $mealSchedule = MealSchedule::create([
                'name' => 'Astoria Plaza Meal Schedule',
                'property_id' => $profile->property_id,
                'remarks' => 'test',
                'days' => Helper::MEAL_SCHEDULE_DAYS,
                'created_by' => $profile->id,
                'updated_by' => $profile->id,
            ]);


            ProfileMealSchedule::create([
                'profile_id' => $profile->id,
                'meal_schedule_id' => $mealSchedule->id,
            ]);


            MealScheduleItem::create([
                'meal_schedule_id' => $mealSchedule->id,
                'time_start' => '07:00:00',
                'time_end' => '10:00:00',
                'meal_type' => Helper::MEAL_SCHEDULE_BREAKFAST,
            ]);

            MealScheduleItem::create([
                'meal_schedule_id' => $mealSchedule->id,
                'time_start' => '11:00:00',
                'time_end' => '14:00:00', // 2pm is 14:00:00
                'meal_type' => Helper::MEAL_SCHEDULE_LUNCH,
            ]);

            MealScheduleItem::create([
                'meal_schedule_id' => $mealSchedule->id,
                'time_start' => '17:00:00', // 5pm is 17:00:00
                'time_end' => '20:00:00', // 8pm is 20:00:00
                'meal_type' => Helper::MEAL_SCHEDULE_DINNER,
            ]);
        }
    }
}
