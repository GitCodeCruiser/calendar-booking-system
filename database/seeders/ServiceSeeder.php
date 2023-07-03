<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {     
        Service::query()->delete();
        DB::statement('ALTER TABLE services AUTO_INCREMENT = 1;');

        Service::insert([
            [
                'name' => 'Men Haircut',
                'buffer_time' => '00:05:00',
                'duration' => '00:10:00',
                'scheduling_window' => 7,
                'max_appointments_per_slot' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Women Haircut',
                'buffer_time' => '00:10:00',
                'duration' => '01:00:00',
                'scheduling_window' => 7,
                'max_appointments_per_slot' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
