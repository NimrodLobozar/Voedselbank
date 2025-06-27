<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Person;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
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

        // Create customer record for the test user
        DB::table('customer')->insert([
            'user_id' => $testUser->id,
            'first_name' => 'Test',
            'middle_name' => null,
            'last_name' => 'User',
            'birth_date' => '1990-01-01',
            'street' => 'Example Street',
            'house_number' => '123',
            'addition' => null,
            'postal_code' => '1234AB',
            'city' => 'Example City',
            'mobile' => '0612345678',
            'email' => 'test@example.com',
            'household_size' => 1,
            'income' => 1500.00,
            'registration_date' => now()->toDateString(),
            'is_actief' => true,
            'opmerking' => 'Test customer details',
            'created_at' => now(),
            'updated_at' => now(),
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
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

        // Create customer record for the admin user
        DB::table('customer')->insert([
            'user_id' => $adminUser->id,
            'first_name' => 'Admin',
            'middle_name' => null,
            'last_name' => 'User',
            'birth_date' => '1985-01-01',
            'street' => 'Admin Street',
            'house_number' => '456',
            'addition' => null,
            'postal_code' => '5678CD',
            'city' => 'Admin City',
            'mobile' => '0698765432',
            'email' => 'admin@example.com',
            'household_size' => 1,
            'income' => 3000.00,
            'registration_date' => now()->toDateString(),
            'is_actief' => true,
            'opmerking' => 'Admin customer details',
            'created_at' => now(),
            'updated_at' => now(),
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
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
        // Supplier::factory(20)->create();
    }
}
