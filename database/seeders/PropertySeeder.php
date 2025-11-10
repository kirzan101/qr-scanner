<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $properties = [
            [
                'name' => 'Astoria Plaza',
                'code' => 'APZ'
            ],
            [
                'name' => 'Astoria Current',
                'code' => 'AC3'
            ],
            [
                'name' => 'Astoria Palawan',
                'code' => 'APW'
            ],
            [
                'name' => 'Astoria Bohol',
                'code' => 'ABH'
            ],
            [
                'name' => 'Astoria Boracay',
                'code' => 'AB1'
            ],
            [
                'name' => 'Astoria Greenbelt',
                'code' => 'AGB'
            ],
            [
                'name' => 'Astoria Siargao',
                'code' => 'APS'
            ],
            [
                'name' => 'Stellar Potter\'s Ridge',
                'code' => 'SPR'
            ],
            [
                'name' => 'Stellar Panglao',
                'code' => 'SPO'
            ],
        ];


        foreach ($properties as $property) {
            Property::create($property);
        }
    }
}
