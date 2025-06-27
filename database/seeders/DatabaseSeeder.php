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
        // Create test users first
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

        // Create suppliers using factory
        Supplier::factory(20)->create();

        // Create FoodStorage test data
        FoodStorage::factory(10)->create();

        // Create additional test customers
        Customer::factory(10)->create([
            'postal_code' => fake()->regexify('[1-9][0-9]{3}[A-Z]{2}'),
        ]);

        FoodPackage::factory(10)->create();

        // Create produce items using factory (includes test data)
        Produce::factory(15)->create();

        // Create produce items manually to ensure datetime fields are set
        $suppliers = Supplier::pluck('id')->toArray();
        $storages = FoodStorage::pluck('id')->toArray();

        // Realistische producten per categorie
        $productenData = [
            'Groente' => [
                'Appels' => ['units' => ['kg', 'stuks'], 'brands' => ['Elstar', 'Jonagold', 'Granny Smith']],
                'Bananen' => ['units' => ['kg', 'bossen'], 'brands' => ['Chiquita', 'Dole', 'Fyffes']],
                'Tomaten' => ['units' => ['kg', 'bakjes'], 'brands' => ['Prominent', 'Tasty Tom', 'Cherry']],
                'Komkommers' => ['units' => ['stuks', 'kg'], 'brands' => ['Hollandse', 'Bio', 'Kwekerij']],
                'Paprika' => ['units' => ['stuks', 'kg'], 'brands' => ['Rood', 'Geel', 'Groen']],
                'Wortelen' => ['units' => ['kg', 'bossen'], 'brands' => ['Winter', 'Bos', 'Baby']],
                'Uien' => ['units' => ['kg', 'zakken'], 'brands' => ['Gele', 'Rode', 'Witte']],
                'Aardappelen' => ['units' => ['kg', 'zakken'], 'brands' => ['Bintje', 'Eigenheimer', 'Annabelle']]
            ],
            'Fruit' => [
                'Sinaasappels' => ['units' => ['kg', 'stuks'], 'brands' => ['Valencia', 'Navel', 'Blood']],
                'Peren' => ['units' => ['kg', 'stuks'], 'brands' => ['Conference', 'Doyenne', 'Williams']],
                'Druiven' => ['units' => ['kg', 'bakjes'], 'brands' => ['Witte', 'Blauwe', 'Rode']],
                'Kiwi' => ['units' => ['stuks', 'bakjes'], 'brands' => ['Zespri', 'Hayward', 'Gold']],
                'Aardbeien' => ['units' => ['bakjes', 'kg'], 'brands' => ['Elsanta', 'Sonata', 'Bio']]
            ],
            'Vlees' => [
                'Kipfilet' => ['units' => ['kg', 'pakken'], 'brands' => ['Pluimvee', 'Bio', 'Vrije uitloop']],
                'Rundvlees' => ['units' => ['kg', 'pakken'], 'brands' => ['Mager', 'Biologisch', 'Weide']],
                'Varkensvlees' => ['units' => ['kg', 'pakken'], 'brands' => ['Schnitzel', 'Haas', 'Biologisch']],
                'Gehakt' => ['units' => ['kg', 'pakken'], 'brands' => ['Half-om-half', 'Rund', 'Varken']]
            ],
            'Zuivel' => [
                'Melk' => ['units' => ['liter', 'pakken'], 'brands' => ['Campina', 'AH Basic', 'Biologisch']],
                'Yoghurt' => ['units' => ['bekers', 'pakken'], 'brands' => ['Danone', 'Campina', 'Griekse']],
                'Kaas' => ['units' => ['kg', 'pakken'], 'brands' => ['Gouda', 'Edam', 'Belegen']],
                'Boter' => ['units' => ['pakken', 'kg'], 'brands' => ['Roomboter', 'Halvarine', 'Biologisch']]
            ],
            'Granen' => [
                'Brood' => ['units' => ['stuks', 'pakken'], 'brands' => ['Volkoren', 'Wit', 'Donker']],
                'Pasta' => ['units' => ['pakken', 'kg'], 'brands' => ['Spaghetti', 'Penne', 'Fusilli']],
                'Rijst' => ['units' => ['kg', 'zakken'], 'brands' => ['Basmati', 'Jasmine', 'Volkoren']],
                'Havermout' => ['units' => ['pakken', 'kg'], 'brands' => ['Brinta', 'Quaker', 'Biologisch']]
            ],
            'Conserven' => [
                'Tomatenpuree' => ['units' => ['blikken', 'tubes'], 'brands' => ['AH', 'Heinz', 'Mutti']],
                'Bonen' => ['units' => ['blikken', 'potten'], 'brands' => ['Witte', 'Bruine', 'Kidney']],
                'Mais' => ['units' => ['blikken', 'potten'], 'brands' => ['Sweet corn', 'Bio', 'Extra zoet']],
                'Tonijn' => ['units' => ['blikken', 'pakken'], 'brands' => ['John West', 'Rio Mare', 'AH']]
            ],
            'Diepvries' => [
                'Erwten' => ['units' => ['zakken', 'kg'], 'brands' => ['Iglo', 'AH', 'Biologisch']],
                'Spinazie' => ['units' => ['zakken', 'kg'], 'brands' => ['Iglo', 'Leaf', 'Biologisch']],
                'Vis' => ['units' => ['pakken', 'kg'], 'brands' => ['Kabeljauw', 'Zalm', 'Tilapia']],
                'Pizza' => ['units' => ['stuks', 'pakken'], 'brands' => ['Margherita', 'Salami', 'Quattro']]
            ],
            'Brood' => [
                'Witbrood' => ['units' => ['stuks', 'pakken'], 'brands' => ['Casino', 'Casino groot', 'AH']],
                'Volkoren' => ['units' => ['stuks', 'pakken'], 'brands' => ['Grof', 'Fijn', 'Biologisch']],
                'Krentenbrood' => ['units' => ['stuks', 'pakken'], 'brands' => ['Rozijnen', 'Krenten', 'Luxe']],
                'Beschuit' => ['units' => ['pakken', 'rollen'], 'brands' => ['Beschuit', 'Volkorenbe', 'AH']]
            ],
            'Overig' => [
                'Koffie' => ['units' => ['pakken', 'kg'], 'brands' => ['Douwe Egberts', 'Senseo', 'AH']],
                'Thee' => ['units' => ['pakken', 'doosjes'], 'brands' => ['Lipton', 'Pickwick', 'Earl Grey']],
                'Suiker' => ['units' => ['kg', 'zakken'], 'brands' => ['Kristal', 'Riet', 'Biologisch']],
                'Zout' => ['units' => ['pakken', 'kg'], 'brands' => ['Zeezout', 'Keukenzout', 'AH']]
            ]
        ];

        for ($i = 0; $i < 15; $i++) {
            $now = now();
            $category = fake()->randomElement(['Groente', 'Fruit', 'Vlees', 'Zuivel', 'Granen', 'Conserven', 'Diepvries', 'Brood', 'Overig']);
            $productName = fake()->randomElement(array_keys($productenData[$category]));
            $productData = $productenData[$category][$productName];

            Produce::create([
                'supplier_id' => fake()->randomElement($suppliers),
                'food_storage_id' => fake()->randomElement($storages),
                'name' => $productName,
                'brand' => fake()->optional(0.7)->randomElement($productData['brands']),
                'category' => $category,
                'expiry_date' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
                'received_date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
                'amount' => fake()->numberBetween(1, 100),
                'unit' => fake()->randomElement($productData['units']),
                'weight_per_unit' => fake()->optional(0.8)->randomFloat(3, 0.1, 5),
                'is_actief' => true,
                'opmerking' => fake()->optional(0.3)->sentence(),
                'datum_aangemaakt' => $now,
                'datum_gewijzigd' => $now,
            ]);
        }
    }
}
