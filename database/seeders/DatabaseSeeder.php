<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Person;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // Use bcrypt for password hashing
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin1234'), // Use bcrypt for password hashing
        ]);

        // Create person record for the user
        Person::create([
            'user_id' => $user->id,
            'is_actief' => true,
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ]);

        // Create admin role for the user
        Role::create([
            'user_id' => $user->id,
            'rol' => 'Admin',
            'is_actief' => true,
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ]);
    }
}
