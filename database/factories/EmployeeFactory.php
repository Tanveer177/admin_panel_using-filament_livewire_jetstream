<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->word, // Ensure it always has a value
            'last_name' => $this->faker->lastName,
            'zip_code' => $this->faker->postcode,
            'address' => $this->faker->address,
            'country_id' => Country::inRandomOrder()->first()?->id ?? Country::factory()->create()->id,
            'state_id' => State::inRandomOrder()->first()?->id ?? State::factory()->create()->id,
            'city_id' => City::inRandomOrder()->first()?->id ?? City::factory()->create()->id,
            'department_id' => Department::inRandomOrder()->first()?->id ?? Department::factory()->create()->id,
            'date_hired' => $this->faker->date(),
            'date_of_birth' => $this->faker->date(),
        ];
    }
    
}
