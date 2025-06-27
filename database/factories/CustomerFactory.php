<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->optional(0.3)->firstName(),
            'last_name' => fake()->lastName(),
            'birth_date' => fake()->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'street' => fake()->streetName(),
            'house_number' => fake()->buildingNumber(),
            'addition' => fake()->optional(0.2)->bothify('?#'),
            'postal_code' => fake()->postcode(),
            'city' => fake()->city(),
            'mobile' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'household_size' => fake()->numberBetween(1, 8),
            'income' => fake()->randomFloat(2, 800, 3000),
            'registration_date' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'is_actief' => fake()->boolean(90),
            'opmerking' => fake()->optional(0.4)->sentence(),
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ];
    }

    public function inactive()
    {
        return $this->state(fn (array $attributes) => [
            'is_actief' => false,
        ]);
    }

    public function withHighIncome()
    {
        return $this->state(fn (array $attributes) => [
            'income' => fake()->randomFloat(2, 2500, 5000),
        ]);
    }

    public function withLowIncome()
    {
        return $this->state(fn (array $attributes) => [
            'income' => fake()->randomFloat(2, 500, 1200),
        ]);
    }
}
