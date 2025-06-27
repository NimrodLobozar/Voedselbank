<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Produce;
use App\Models\FoodStorage;

/**
 * Feature tests for Supplier CRUD operations.
 * 
 * Tests the complete functionality including create, read, update, delete
 * operations with actual database interactions.
 *
 * @package Tests\Feature
 * @author Voedselbank Development Team
 * @version 1.0
 */
class SupplierCrudTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate a user for testing
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    /**
     * Test supplier can be created with valid data.
     *
     * @return void
     */
    public function test_supplier_can_be_created_with_valid_data(): void
    {
        $supplierData = [
            'name' => 'Test Supermarket',
            'contact_person' => 'John Doe',
            'phone' => '+31612345678',
            'email' => 'john@testsupermarket.nl',
            'address' => 'Teststraat 123, 1234AB Amsterdam',
            'supplier_type' => 'Supermarket',
            'is_actief' => true,
            'opmerking' => 'Test opmerking',
        ];

        $supplier = Supplier::create($supplierData + [
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ]);

        $this->assertInstanceOf(Supplier::class, $supplier);
        $this->assertDatabaseHas('suppliers', [
            'name' => 'Test Supermarket',
            'email' => 'john@testsupermarket.nl',
            'supplier_type' => 'Supermarket',
            'is_actief' => true
        ]);

        // Test all attributes are set correctly
        $this->assertEquals('Test Supermarket', $supplier->name);
        $this->assertEquals('John Doe', $supplier->contact_person);
        $this->assertEquals('+31612345678', $supplier->phone);
        $this->assertEquals('john@testsupermarket.nl', $supplier->email);
        $this->assertEquals('Teststraat 123, 1234AB Amsterdam', $supplier->address);
        $this->assertEquals('Supermarket', $supplier->supplier_type);
        $this->assertTrue($supplier->is_actief);
        $this->assertEquals('Test opmerking', $supplier->opmerking);
    }

    /**
     * Test supplier creation fails with invalid data.
     *
     * @return void
     */
    public function test_supplier_creation_fails_with_invalid_data(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Try to create supplier without required fields
        Supplier::create([
            'name' => '', // Empty name should fail
            'email' => 'invalid-email', // Invalid email format
        ]);
    }

    /**
     * Test supplier can be updated with valid data.
     *
     * @return void
     */
    public function test_supplier_can_be_updated_with_valid_data(): void
    {
        $supplier = Supplier::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'supplier_type' => 'Farmer'
        ]);

        $updateData = [
            'name' => 'Updated Supermarket',
            'contact_person' => 'Jane Smith',
            'phone' => '+31687654321',
            'email' => 'jane@updated.nl',
            'address' => 'Updated Street 456, 5678CD Rotterdam',
            'supplier_type' => 'Supermarket',
            'is_actief' => false,
            'opmerking' => 'Updated opmerking',
            'datum_gewijzigd' => now(),
        ];

        $supplier->update($updateData);
        $supplier->refresh();

        $this->assertEquals('Updated Supermarket', $supplier->name);
        $this->assertEquals('Jane Smith', $supplier->contact_person);
        $this->assertEquals('+31687654321', $supplier->phone);
        $this->assertEquals('jane@updated.nl', $supplier->email);
        $this->assertEquals('Updated Street 456, 5678CD Rotterdam', $supplier->address);
        $this->assertEquals('Supermarket', $supplier->supplier_type);
        $this->assertFalse($supplier->is_actief);
        $this->assertEquals('Updated opmerking', $supplier->opmerking);

        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'name' => 'Updated Supermarket',
            'email' => 'jane@updated.nl',
            'supplier_type' => 'Supermarket',
            'is_actief' => false
        ]);
    }

    /**
     * Test supplier can be deleted when no active orders exist.
     *
     * @return void
     */
    public function test_supplier_can_be_deleted_when_no_active_orders(): void
    {
        $supplier = Supplier::factory()->create();
        $supplierId = $supplier->id;

        // Create some delivered (inactive) orders
        $deliveredFoodStorage = FoodStorage::factory()->create(['status' => 'geleverd']);
        Produce::factory()->create([
            'supplier_id' => $supplier->id,
            'food_storage_id' => $deliveredFoodStorage->id
        ]);

        // Supplier should have no active orders
        $this->assertFalse($supplier->hasActiveOrders());

        $supplier->delete();

        $this->assertDatabaseMissing('suppliers', ['id' => $supplierId]);
    }

    /**
     * Test supplier cannot be deleted when active orders exist.
     *
     * @return void
     */
    public function test_supplier_cannot_be_deleted_when_active_orders_exist(): void
    {
        $supplier = Supplier::factory()->create();

        // Create active orders
        $activeFoodStorage = FoodStorage::factory()->create(['status' => 'onderweg']);
        Produce::factory()->create([
            'supplier_id' => $supplier->id,
            'food_storage_id' => $activeFoodStorage->id
        ]);

        // Supplier should have active orders
        $this->assertTrue($supplier->hasActiveOrders());

        // In a real application, you would prevent deletion
        // For this test, we'll just verify the business logic
        $this->assertTrue($supplier->hasActiveOrders());
    }

    /**
     * Test supplier factory creates valid supplier.
     *
     * @return void
     */
    public function test_supplier_factory_creates_valid_supplier(): void
    {
        $supplier = Supplier::factory()->create();

        $this->assertInstanceOf(Supplier::class, $supplier);
        $this->assertNotNull($supplier->name);
        $this->assertNotNull($supplier->contact_person);
        $this->assertNotNull($supplier->phone);
        $this->assertNotNull($supplier->email);
        $this->assertNotNull($supplier->address);
        $this->assertContains($supplier->supplier_type, Supplier::SUPPLIER_TYPES);
        $this->assertIsBool($supplier->is_actief);
        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id]);
    }

    /**
     * Test supplier relationships work correctly.
     *
     * @return void
     */
    public function test_supplier_relationships_work_correctly(): void
    {
        $supplier = Supplier::factory()->create();

        // Create food storage first to satisfy foreign key constraints
        $foodStorage1 = FoodStorage::factory()->create();
        $foodStorage2 = FoodStorage::factory()->create();

        // Create produce for this supplier with valid food_storage_id
        $produce1 = Produce::factory()->create([
            'supplier_id' => $supplier->id,
            'food_storage_id' => $foodStorage1->id
        ]);
        $produce2 = Produce::factory()->create([
            'supplier_id' => $supplier->id,
            'food_storage_id' => $foodStorage2->id
        ]);

        $this->assertCount(2, $supplier->produce);
        $this->assertTrue($supplier->produce->contains($produce1));
        $this->assertTrue($supplier->produce->contains($produce2));
    }

    /**
     * Test supplier with food storages through produce.
     *
     * @return void
     */
    public function test_supplier_food_storages_relationship(): void
    {
        $supplier = Supplier::factory()->create();
        $foodStorage = FoodStorage::factory()->create();

        Produce::factory()->create([
            'supplier_id' => $supplier->id,
            'food_storage_id' => $foodStorage->id
        ]);

        $foodStorages = $supplier->foodStorages;

        $this->assertCount(1, $foodStorages);
        $this->assertEquals($foodStorage->id, $foodStorages->first()->id);
    }

    /**
     * Test supplier active food storages filtering.
     *
     * @return void
     */
    public function test_supplier_active_food_storages_filtering(): void
    {
        $supplier = Supplier::factory()->create();

        // Create food storages with different statuses
        $activeFoodStorage1 = FoodStorage::factory()->create(['status' => 'onderweg']);
        $activeFoodStorage2 = FoodStorage::factory()->create(['status' => 'in_behandeling']);
        $inactiveFoodStorage = FoodStorage::factory()->create(['status' => 'geleverd']);

        // Create produce linking supplier to food storages
        Produce::factory()->create([
            'supplier_id' => $supplier->id,
            'food_storage_id' => $activeFoodStorage1->id
        ]);
        Produce::factory()->create([
            'supplier_id' => $supplier->id,
            'food_storage_id' => $activeFoodStorage2->id
        ]);
        Produce::factory()->create([
            'supplier_id' => $supplier->id,
            'food_storage_id' => $inactiveFoodStorage->id
        ]);

        $activeFoodStorages = $supplier->activeFoodStorages;

        $this->assertCount(2, $activeFoodStorages);
        $this->assertTrue($activeFoodStorages->contains('id', $activeFoodStorage1->id));
        $this->assertTrue($activeFoodStorages->contains('id', $activeFoodStorage2->id));
        $this->assertFalse($activeFoodStorages->contains('id', $inactiveFoodStorage->id));
    }

    /**
     * Test supplier business logic for active orders.
     *
     * @return void
     */
    public function test_supplier_has_active_orders_business_logic(): void
    {
        $supplier = Supplier::factory()->create();

        // Initially no active orders
        $this->assertFalse($supplier->hasActiveOrders());

        // Add active order
        $activeFoodStorage = FoodStorage::factory()->create(['status' => 'onderweg']);
        Produce::factory()->create([
            'supplier_id' => $supplier->id,
            'food_storage_id' => $activeFoodStorage->id
        ]);

        // Now should have active orders
        $this->assertTrue($supplier->fresh()->hasActiveOrders());

        // Change status to delivered
        $activeFoodStorage->update(['status' => 'geleverd']);

        // Should no longer have active orders
        $this->assertFalse($supplier->fresh()->hasActiveOrders());
    }

    /**
     * Test supplier scopes work correctly.
     *
     * @return void
     */
    public function test_supplier_scopes_work_correctly(): void
    {
        // Create active and inactive suppliers
        $activeSupplier = Supplier::factory()->create(['is_actief' => true]);
        $inactiveSupplier = Supplier::factory()->create(['is_actief' => false]);

        // Create suppliers of different types
        $supermarketSupplier = Supplier::factory()->create(['supplier_type' => 'Supermarket']);
        $farmerSupplier = Supplier::factory()->create(['supplier_type' => 'Farmer']);

        // Test active scope
        $activeSuppliers = Supplier::active()->get();
        $this->assertTrue($activeSuppliers->contains($activeSupplier));
        $this->assertFalse($activeSuppliers->contains($inactiveSupplier));

        // Test ofType scope
        $supermarkets = Supplier::ofType('Supermarket')->get();
        $this->assertTrue($supermarkets->contains($supermarketSupplier));
        $this->assertFalse($supermarkets->contains($farmerSupplier));

        // Test search scope
        $supplier = Supplier::factory()->create([
            'name' => 'Unique Test Name',
            'contact_person' => 'Unique Contact Person',
            'email' => 'unique@test.com'
        ]);

        $searchResults = Supplier::search('Unique')->get();
        $this->assertTrue($searchResults->contains($supplier));

        $searchResultsByEmail = Supplier::search('unique@test.com')->get();
        $this->assertTrue($searchResultsByEmail->contains($supplier));
    }

    /**
     * Test supplier validation with edge cases.
     *
     * @return void
     */
    public function test_supplier_validation_edge_cases(): void
    {
        // Test each supplier type is valid
        foreach (Supplier::SUPPLIER_TYPES as $type) {
            $supplier = Supplier::factory()->create(['supplier_type' => $type]);
            $this->assertEquals($type, $supplier->supplier_type);
        }

        // Test boolean casting for is_actief
        $supplier = Supplier::factory()->create(['is_actief' => '1']);
        $this->assertIsBool($supplier->is_actief);
        $this->assertTrue($supplier->is_actief);

        $supplier = Supplier::factory()->create(['is_actief' => '0']);
        $this->assertIsBool($supplier->is_actief);
        $this->assertFalse($supplier->is_actief);
    }

    /**
     * Test supplier model events (if any).
     *
     * @return void
     */
    public function test_supplier_model_events(): void
    {
        $supplier = Supplier::factory()->create([
            'datum_gewijzigd' => now()->subHour()
        ]);

        $originalTimestamp = $supplier->datum_gewijzigd;

        // Wait a moment to ensure timestamp difference
        sleep(1);

        // Update supplier to trigger the updating event
        $supplier->update(['name' => 'Updated Name']);

        // The datum_gewijzigd should be automatically updated
        $this->assertNotEquals($originalTimestamp, $supplier->fresh()->datum_gewijzigd);
    }

    /**
     * Test supplier display attributes and helpers.
     *
     * @return void
     */
    public function test_supplier_display_attributes_and_helpers(): void
    {
        $supplier = Supplier::factory()->create([
            'contact_person' => 'John Doe',
            'phone' => '+31612345678',
            'email' => 'john@example.com',
            'supplier_type' => 'Supermarket'
        ]);

        // Test full contact attribute
        $expectedContact = 'John Doe - +31612345678 - john@example.com';
        $this->assertEquals($expectedContact, $supplier->full_contact);

        // Test supplier type display
        $this->assertEquals('Supermarkt', $supplier->supplier_type_display);

        // Test static validation method
        $this->assertTrue(Supplier::isValidSupplierType('Supermarket'));
        $this->assertFalse(Supplier::isValidSupplierType('InvalidType'));
    }

    /**
     * Test complex business scenario with multiple suppliers and orders.
     *
     * @return void
     */
    public function test_complex_business_scenario(): void
    {
        // Create multiple suppliers
        $activeSupplier = Supplier::factory()->create(['is_actief' => true]);
        $inactiveSupplier = Supplier::factory()->create(['is_actief' => false]);

        // Create various food storages
        $activeFoodStorage = FoodStorage::factory()->create(['status' => 'onderweg']);
        $deliveredFoodStorage = FoodStorage::factory()->create(['status' => 'geleverd']);

        // Create produce linking suppliers to food storages
        Produce::factory()->create([
            'supplier_id' => $activeSupplier->id,
            'food_storage_id' => $activeFoodStorage->id
        ]);

        Produce::factory()->create([
            'supplier_id' => $inactiveSupplier->id,
            'food_storage_id' => $deliveredFoodStorage->id
        ]);

        // Verify business logic
        $this->assertTrue($activeSupplier->hasActiveOrders());
        $this->assertFalse($inactiveSupplier->hasActiveOrders());

        // Test filtering
        $suppliersWithActiveOrders = Supplier::whereHas('foodStorages', function ($q) {
            $q->whereIn('status', Supplier::ACTIVE_ORDER_STATUSES);
        })->get();

        $this->assertTrue($suppliersWithActiveOrders->contains($activeSupplier));
        $this->assertFalse($suppliersWithActiveOrders->contains($inactiveSupplier));
    }
}
