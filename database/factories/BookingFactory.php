<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WeeklyAvailability;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_time' => $this->faker->dateTime(),
            'end_time' => $this->faker->dateTime(),
            'weekly_availability_id' => WeeklyAvailability::factory(),
            'user_id' => User::factory()
        ];
    }
}
