<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\WeeklyAvailability;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeeklyAvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::truncate();
        
        WeeklyAvailability::query()->delete();
        DB::statement('ALTER TABLE weekly_availabilities AUTO_INCREMENT = 1;');

        WeeklyAvailability::insert([
            [
                'day_of_week' => '1',
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'service_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'day_of_week' => '2',
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'service_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'day_of_week' => '3',
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'service_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'day_of_week' => '4',
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'service_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'day_of_week' => '5',
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'service_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'day_of_week' => '6',
                'start_time' => '10:00:00',
                'end_time' => '22:00:00',
                'service_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'day_of_week' => '1',
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'service_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'day_of_week' => '2',
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'service_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'day_of_week' => '3',
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'service_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'day_of_week' => '4',
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'service_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'day_of_week' => '5',
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'service_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'day_of_week' => '6',
                'start_time' => '10:00:00',
                'end_time' => '22:00:00',
                'service_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
