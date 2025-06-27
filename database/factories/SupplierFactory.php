<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'contact_person' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'supplier_type' => $this->faker->randomElement(['Supermarket', 'Farmer', 'Wholesaler', 'Individual']),
            'is_actief' => $this->faker->boolean(80), // 80% chance of being active
            'opmerking' => $this->faker->optional()->sentence(),
            'datum_aangemaakt' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'datum_gewijzigd' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
