<?php

namespace Database\Factories;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->city,
            // 'state_id' => rand(1, 100), // For random generation of state_id foreign key column value 
            'state_id' => State::inRandomOrder()->first()?->id ?? State::factory()->create()->id,
        ];
    }
}
