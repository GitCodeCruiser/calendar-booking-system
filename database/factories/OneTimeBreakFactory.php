<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Service;
use App\Models\OneTimeBreak;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OneTimeBreak>
 */
class OneTimeBreakFactory extends Factory
{
    protected $model = OneTimeBreak::class;

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
            'service_id' => Service::factory(),
        ];
    }
}
