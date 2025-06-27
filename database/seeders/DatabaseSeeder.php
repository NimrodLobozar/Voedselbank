<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Person;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            'adults_count' => 2,
            'children_count' => 1,
            'babies_count' => 0,
            'no_pork' => true,
            'is_vegetarian' => true,
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
            'adults_count' => 2,
            'children_count' => 2,
            'babies_count' => 1,
            'is_vegan' => false,
            'no_pork' => false,
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

        // Create additional test customers with varied preferences
        Customer::factory(10)->create()->each(function ($customer) {
            $customer->update([
                'adults_count' => fake()->numberBetween(1, 3),
                'children_count' => fake()->numberBetween(0, 4),
                'babies_count' => fake()->numberBetween(0, 2),
                'no_pork' => fake()->boolean(30),
                'is_vegan' => fake()->boolean(10),
                'is_vegetarian' => fake()->boolean(20),
            ]);
        });

        // Seed allergy data
        $allergies = ['Pinda', 'Noten', 'Melk', 'Eieren', 'Soja', 'Tarwe', 'Vis', 'Schaaldieren', 'Sesam', 'Gluten', 'Lactose', 'Sulfieten'];
        foreach ($allergies as $allergy) {
            DB::table('allergy')->insert([
                'allergy_name' => $allergy,
                'is_actief' => true,
                'datum_aangemaakt' => now(),
                'datum_gewijzigd' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
