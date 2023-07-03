<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\WeeklyAvailability;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WeeklyAvailability>
 */
class WeeklyAvailabilityFactory extends Factory
{
    protected $model = WeeklyAvailability::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'day_of_week' => $this->faker->numberBetween(0, 6),
            'start_time' => $this->faker->time('H:i:s'),
            'end_time' => $this->faker->time('H:i:s'),
            'service_id' => Service::factory(),
            'is_disabled' => $this->faker->boolean,
        ];
    }
}
