<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FoodStorage>
 */
class FoodstorageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $storageTypes = ['Refrigerated', 'Frozen', 'Dry', 'Fresh'];
        $storageType = $this->faker->randomElement($storageTypes);

        // Set temperature ranges based on storage type
        $tempRanges = [
            'Refrigerated' => ['min' => 2.0, 'max' => 8.0],
            'Frozen' => ['min' => -25.0, 'max' => -18.0],
            'Dry' => ['min' => 15.0, 'max' => 25.0],
            'Fresh' => ['min' => 10.0, 'max' => 18.0],
        ];

        $tempRange = $tempRanges[$storageType];

        $storageNames = [
            'Refrigerated' => ['Koelruimte A', 'Koelruimte B', 'Zuivel Koeling', 'Vlees Koeling'],
            'Frozen' => ['Vriesruimte 1', 'Vriesruimte 2', 'Diepvries Hal'],
            'Dry' => ['Droge Opslag', 'Conserven Magazijn', 'Granen Hal', 'Hoofdmagazijn'],
            'Fresh' => ['Verse Producten', 'Groente Ruimte', 'Fruit Zone', 'Brood Opslag']
        ];

        return [
            'name' => $this->faker->randomElement($storageNames[$storageType]) . ' ' . $this->faker->randomElement(['Noord', 'Zuid', 'Oost', 'West']),
            'location' => $this->faker->streetAddress . ', ' . $this->faker->postcode . ' ' . $this->faker->city,
            'capacity' => $this->faker->numberBetween(100, 1000),
            'temperature_min' => $tempRange['min'],
            'temperature_max' => $tempRange['max'],
            'storage_type' => $storageType,
            'status' => $this->faker->randomElement(['onderweg', 'in_behandeling', 'geleverd']),
            'is_actief' => $this->faker->boolean(90),
            'opmerking' => $this->faker->optional(0.3)->sentence(),
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ];
    }
}
