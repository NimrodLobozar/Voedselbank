<?php

namespace Database\Factories;

use App\Models\Produce;
use App\Models\Supplier;
use App\Models\FoodStorage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduceFactory extends Factory
{
    protected $model = Produce::class;

    public function definition(): array
    {
        // Create suppliers if they don't exist
        $this->ensureSuppliersExist();
        
        // Get random existing supplier and storage IDs
        $supplierIds = Supplier::pluck('id')->toArray();
        $storageIds = FoodStorage::pluck('id')->toArray();

        $now = now();

        return [
            'supplier_id' => $this->faker->randomElement($supplierIds),
            'food_storage_id' => $this->faker->randomElement($storageIds ?: [1]),
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
            'created_at' => $now,
            'updated_at' => $now,
            'datum_aangemaakt' => $now,
            'datum_gewijzigd' => $now,
        ];
    }

    /**
     * Ensure test suppliers exist
     */
    private function ensureSuppliersExist(): void
    {
        if (Supplier::count() === 0) {
            $testSuppliers = [
                [
                    'name' => 'Albert Heijn Centrum',
                    'contact_person' => 'Maria van der Berg',
                    'phone' => '020-1234567',
                    'email' => 'donaties@ah.nl',
                    'address' => 'Hoofdstraat 123, 1000 AA Amsterdam',
                    'supplier_type' => 'Supermarket',
                    'is_actief' => true,
                    'datum_aangemaakt' => now(),
                    'datum_gewijzigd' => now(),
                ],
                [
                    'name' => 'Boerderij De Groene Weide',
                    'contact_person' => 'Jan Bakker',
                    'phone' => '0312-567890',
                    'email' => 'info@groeneweide.nl',
                    'address' => 'Polderweg 45, 3600 BB Maarssen',
                    'supplier_type' => 'Farmer',
                    'is_actief' => true,
                    'datum_aangemaakt' => now(),
                    'datum_gewijzigd' => now(),
                ],
                [
                    'name' => 'Groothandel Fresh Foods',
                    'contact_person' => 'Sarah Ahmed',
                    'phone' => '010-9876543',
                    'email' => 'donaties@freshfoods.nl',
                    'address' => 'Industrieweg 78, 3000 CC Rotterdam',
                    'supplier_type' => 'Wholesaler',
                    'is_actief' => true,
                    'datum_aangemaakt' => now(),
                    'datum_gewijzigd' => now(),
                ]
            ];

            foreach ($testSuppliers as $supplier) {
                Supplier::create($supplier);
            }
        }
    }
}