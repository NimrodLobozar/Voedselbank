<?php

namespace Database\Factories;

use App\Models\Produce;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduceFactory extends Factory
{
    protected $model = Produce::class;

    public function definition(): array
    {
        return [
            'supplier_id' => 1, // Adjust as needed, or use a random existing supplier ID
            'food_storage_id' => 1, // Adjust as needed, or use a random existing food_storage ID
            'name' => $this->faker->word(),
            'brand' => $this->faker->optional()->company(),
            'category' => $this->faker->randomElement(['Groente', 'Fruit', 'Vlees', 'Zuivel', 'Granen', 'Conserven', 'Diepvries', 'Brood', 'Overig']),
            'expiry_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'received_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'amount' => $this->faker->numberBetween(1, 100),
            'unit' => $this->faker->randomElement(['stuks', 'kg', 'liter', 'zakken']),
            'weight_per_unit' => $this->faker->optional()->randomFloat(3, 0.1, 5),
            'is_actief' => true,
            'opmerking' => $this->faker->optional()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ];
    }
}