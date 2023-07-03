<?php

namespace Database\Seeders;

use App\Models\WeeklyBreak;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WeeklyBreakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WeeklyBreak::truncate();

        WeeklyBreak::insert([
            [
                'name' => 'Lunch Break',
                'start_time' => '12:00:00',
                'end_time' => '13:00:00',
                'service_id' => 1,
            ],
            [
                'name' => 'Cleanup Break',
                'start_time' => '15:00:00',
                'end_time' => '16:00:00',
                'service_id' => 1,
            ],
            [
                'name' => 'Lunch Break',
                'start_time' => '12:00:00',
                'end_time' => '13:00:00',
                'service_id' => 2,
            ],
            [
                'name' => 'Cleanup Break',
                'start_time' => '15:00:00',
                'end_time' => '16:00:00',
                'service_id' => 2,
            ],
        ]);
    }
}
