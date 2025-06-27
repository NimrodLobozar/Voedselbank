<?php

namespace Database\Factories;

use App\Models\FoodPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodPackageFactory extends Factory
{
    protected $model = FoodPackage::class;

    public function definition(): array
    {
        return [
            'customer_id' => 1, // Or use a factory or random existing customer ID
            'prepared_by' => null, // Or a user ID if you want to relate to a user
            'package_name' => $this->faker->words(2, true),
            'assembled_at' => $this->faker->date(),
            'distribution_date' => $this->faker->date(),
            'pickup_time' => $this->faker->optional()->time(),
            'status' => $this->faker->randomElement(['Assembled', 'Ready', 'Distributed', 'Cancelled']),
            'is_actief' => true,
            'opmerking' => $this->faker->optional()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ];
    }
}