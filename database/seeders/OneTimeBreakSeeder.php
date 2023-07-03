<?php

namespace Database\Seeders;

use App\Models\OneTimeBreak;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OneTimeBreakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OneTimeBreak::truncate();
        $thirdDay = Carbon::today()->addDays(2)->format('Y-m-d');

        OneTimeBreak::insert([
            [
                'start_time' => $thirdDay . ' 00:00:00',
                'end_time' => $thirdDay . ' 23:59:59',
                'service_id' => 1,
            ],
            [
                'start_time' => $thirdDay . ' 00:00:00',
                'end_time' => $thirdDay . ' 23:59:59',
                'service_id' => 2,
            ]
        ]);
    }
}
