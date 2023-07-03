<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Service;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'buffer_time' => $this->faker->time('H:i:s'),
            'duration' => $this->faker->time('H:i:s'),
            'scheduling_window' => $this->faker->numberBetween(1, 30),
            'max_appointments_per_slot' => $this->faker->numberBetween(1, 5),
        ];
    }
}
