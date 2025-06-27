<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Person;
use App\Models\Customer;
use App\Models\FoodStorage;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Models\Produce;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FoodPackage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user (customer)
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('Test1234'),
        ]);

        // Create customer record for the test user using factory
        Customer::factory()->create([
            'user_id' => $testUser->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'postal_code' => '1234AB',
        ]);

        // Create person record for the test user
        Person::create([
            'user_id' => $testUser->id,
            'is_actief' => true,
            'opmerking' => 'Test user person record',
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ]);

        // Create role for the test user
        Role::create([
            'user_id' => $testUser->id,
            'rol' => 'Vrijwilliger',
            'is_actief' => true,
            'opmerking' => 'Test user role details',
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ]);

        // Create an admin user
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('Admin1234'),
        ]);

        // Create customer record for the admin user using factory
        Customer::factory()->create([
            'user_id' => $adminUser->id,
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'income' => 3000.00,
            'postal_code' => '5678CD',
        ]);

        // Create person record for the admin user
        Person::create([
            'user_id' => $adminUser->id,
            'is_actief' => true,
            'opmerking' => 'Admin user person record',
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ]);

        // Create admin role for the admin user
        Role::create([
            'user_id' => $adminUser->id,
            'rol' => 'Admin',
            'is_actief' => true,
            'opmerking' => 'Admin user role details',
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ]);

        // Create a test supplier
        Supplier::factory(20)->create();

        // Create additional test customers
        Customer::factory(10)->create([
            'postal_code' => fake()->regexify('[1-9][0-9]{3}[A-Z]{2}'),
        ]);
        
        // Create FoodStorage test data
        FoodStorage::factory(10)->create();

        // Create Suppliers first
        $suppliers = [
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

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Get suppliers and storages for produces
        $supplierIds = Supplier::pluck('id')->toArray();
        $storageIds = FoodStorage::pluck('id')->toArray();

        // Create test produce items (alleen test data)
        $testProduces = [
            ['name' => 'Appels', 'category' => 'Fruit', 'amount' => 50, 'unit' => 'kg'],
            ['name' => 'Brood', 'category' => 'Brood', 'amount' => 20, 'unit' => 'stuks'],
            ['name' => 'Melk', 'category' => 'Zuivel', 'amount' => 30, 'unit' => 'liter'],
            ['name' => 'Rijst', 'category' => 'Granen', 'amount' => 25, 'unit' => 'kg'],
            ['name' => 'Tomaten', 'category' => 'Groente', 'amount' => 15, 'unit' => 'kg'],
        ];

        $storageIds = FoodStorage::pluck('id')->toArray();

        foreach ($testProduces as $produce) {
            if (!empty($storageIds)) {
                Produce::create([
                    'supplier_id' => 1, // Assuming supplier ID 1 exists
                    'food_storage_id' => $storageIds[0], // First storage
                    'name' => $produce['name'],
                    'brand' => null,
                    'category' => $produce['category'],
                    'expiry_date' => now()->addDays(rand(5, 30)),
                    'received_date' => now()->subDays(rand(0, 5)),
                    'amount' => $produce['amount'],
                    'unit' => $produce['unit'],
                    'weight_per_unit' => 1.0,
                    'is_actief' => true,
                    'datum_aangemaakt' => now(),
                    'datum_gewijzigd' => now(),
                ]);
            }
        }
    }
}