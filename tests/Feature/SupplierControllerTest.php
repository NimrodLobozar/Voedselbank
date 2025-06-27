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
 * Feature tests for Supplier HTTP endpoints.
 * 
 * Tests the web interface and API endpoints for supplier management
 * including all CRUD operations through HTTP requests.
 *
 * @package Tests\Feature
 * @author Voedselbank Development Team
 * @version 1.0
 */
class SupplierControllerTest extends TestCase
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
     * Test supplier index page displays correctly.
     *
     * @return void
     */
    public function test_supplier_index_displays_correctly(): void
    {
        $suppliers = Supplier::factory()->count(3)->create();

        $response = $this->get(route('suppliers.index'));

        $response->assertStatus(200);
        $response->assertViewIs('suppliers.index');
        $response->assertViewHas('suppliers');
        $response->assertViewHas('supplierTypes');

        foreach ($suppliers as $supplier) {
            $response->assertSee($supplier->name);
        }
    }

    /**
     * Test supplier index with search functionality.
     *
     * @return void
     */
    public function test_supplier_index_with_search(): void
    {
        $searchableSupplier = Supplier::factory()->create([
            'name' => 'Searchable Supermarket'
        ]);
        $otherSupplier = Supplier::factory()->create([
            'name' => 'Other Company'
        ]);

        $response = $this->get(route('suppliers.index', ['search' => 'Searchable']));

        $response->assertStatus(200);
        $response->assertSee($searchableSupplier->name);
        $response->assertDontSee($otherSupplier->name);
    }

    /**
     * Test supplier index with type filter.
     *
     * @return void
     */
    public function test_supplier_index_with_type_filter(): void
    {
        $supermarket = Supplier::factory()->create(['supplier_type' => 'Supermarket']);
        $farmer = Supplier::factory()->create(['supplier_type' => 'Farmer']);

        $response = $this->get(route('suppliers.index', ['supplier_type' => 'Supermarket']));

        $response->assertStatus(200);
        $response->assertSee($supermarket->name);
        $response->assertDontSee($farmer->name);
    }

    /**
     * Test supplier create page displays correctly.
     *
     * @return void
     */
    public function test_supplier_create_page_displays(): void
    {
        $response = $this->get(route('suppliers.create'));

        $response->assertStatus(200);
        $response->assertViewIs('suppliers.create');
    }

    /**
     * Test supplier can be created via HTTP request.
     *
     * @return void
     */
    public function test_supplier_can_be_created_via_http(): void
    {
        $supplierData = [
            'name' => 'Test HTTP Supermarket',
            'contact_person' => 'John HTTP',
            'phone' => '+31612345678',
            'email' => 'john@httptest.nl',
            'address' => 'HTTP Street 123, 1234AB Amsterdam',
            'supplier_type' => 'Supermarket',
            'is_actief' => true,
            'opmerking' => 'HTTP test opmerking',
        ];

        $response = $this->post(route('suppliers.store'), $supplierData);

        $response->assertStatus(302); // Redirect after successful creation

        $supplier = Supplier::where('email', 'john@httptest.nl')->first();
        $this->assertNotNull($supplier);

        $response->assertRedirect(route('suppliers.show', $supplier));

        $this->assertDatabaseHas('suppliers', [
            'name' => 'Test HTTP Supermarket',
            'email' => 'john@httptest.nl'
        ]);
    }

    /**
     * Test supplier creation fails with invalid data.
     *
     * @return void
     */
    public function test_supplier_creation_fails_with_invalid_data(): void
    {
        $invalidData = [
            'name' => '', // Required field empty
            'contact_person' => 'A', // Too short
            'phone' => '123', // Too short
            'email' => 'invalid-email', // Invalid format
            'address' => 'Ab', // Too short
            'supplier_type' => 'InvalidType', // Invalid type
        ];

        $response = $this->post(route('suppliers.store'), $invalidData);

        $response->assertStatus(302); // Redirect back with errors
        $response->assertSessionHasErrors([
            'name',
            'contact_person',
            'phone',
            'email',
            'address',
            'supplier_type'
        ]);
    }

    /**
     * Test supplier show page displays correctly.
     *
     * @return void
     */
    public function test_supplier_show_page_displays(): void
    {
        $supplier = Supplier::factory()->create();

        $response = $this->get(route('suppliers.show', $supplier));

        $response->assertStatus(200);
        $response->assertViewIs('suppliers.show');
        $response->assertViewHas('supplier');
        $response->assertSee($supplier->name);
        $response->assertSee($supplier->email);
    }

    /**
     * Test supplier edit page displays correctly.
     *
     * @return void
     */
    public function test_supplier_edit_page_displays(): void
    {
        $supplier = Supplier::factory()->create();

        $response = $this->get(route('suppliers.edit', $supplier));

        $response->assertStatus(200);
        $response->assertViewIs('suppliers.edit');
        $response->assertViewHas('supplier');
        $response->assertSee($supplier->name);
    }

    /**
     * Test supplier can be updated via HTTP request.
     *
     * @return void
     */
    public function test_supplier_can_be_updated_via_http(): void
    {
        $supplier = Supplier::factory()->create([
            'name' => 'Original Name'
        ]);

        $updateData = [
            'name' => 'Updated HTTP Name',
            'contact_person' => 'Jane HTTP',
            'phone' => '+31687654321',
            'email' => 'jane@example.com',
            'address' => 'Updated HTTP Street 456, 5678CD Rotterdam',
            'supplier_type' => 'Farmer',
            'is_actief' => false,
            'opmerking' => 'Updated HTTP opmerking',
        ];

        $response = $this->patch(route('suppliers.update', $supplier), $updateData);

        $response->assertStatus(302); // Redirect after successful update
        $response->assertRedirect(route('suppliers.show', $supplier));

        $supplier->refresh();
        $this->assertEquals('Updated HTTP Name', $supplier->name);
        $this->assertEquals('jane@example.com', $supplier->email);
        $this->assertEquals('Farmer', $supplier->supplier_type);
        $this->assertFalse($supplier->is_actief);
    }

    /**
     * Test supplier update fails with invalid data.
     *
     * @return void
     */
    public function test_supplier_update_fails_with_invalid_data(): void
    {
        $supplier = Supplier::factory()->create();

        $invalidData = [
            'name' => '', // Required field empty
            'email' => 'invalid-email', // Invalid format
            'supplier_type' => 'InvalidType', // Invalid type
        ];

        $response = $this->patch(route('suppliers.update', $supplier), $invalidData);

        $response->assertStatus(302); // Redirect back with errors
        $response->assertSessionHasErrors(['name', 'email', 'supplier_type']);
    }

    /**
     * Test supplier can be deleted when no active orders.
     *
     * @return void
     */
    public function test_supplier_can_be_deleted_when_no_active_orders(): void
    {
        $supplier = Supplier::factory()->create();

        // Create some delivered (inactive) orders
        $deliveredFoodStorage = FoodStorage::factory()->create(['status' => 'geleverd']);
        Produce::factory()->create([
            'supplier_id' => $supplier->id,
            'food_storage_id' => $deliveredFoodStorage->id
        ]);

        $response = $this->delete(route('suppliers.destroy', $supplier));

        $response->assertStatus(302); // Redirect after deletion
        $response->assertRedirect(route('suppliers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
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

        $response = $this->delete(route('suppliers.destroy', $supplier));

        $response->assertStatus(302); // Redirect with error
        $response->assertRedirect(route('suppliers.index'));
        $response->assertSessionHas('error');

        // Supplier should still exist
        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id]);
    }

    /**
     * Test supplier deletion with non-existent supplier.
     *
     * @return void
     */
    public function test_supplier_deletion_with_non_existent_supplier(): void
    {
        $response = $this->delete(route('suppliers.destroy', 99999));

        // Controller redirects with error message instead of 404
        $response->assertStatus(302);
        $response->assertRedirect(route('suppliers.index'));
        $response->assertSessionHas('error');
    }

    /**
     * Test supplier show with non-existent supplier.
     *
     * @return void
     */
    public function test_supplier_show_with_non_existent_supplier(): void
    {
        $response = $this->get(route('suppliers.show', 99999));

        // Controller redirects with error message instead of 404
        $response->assertStatus(302);
        $response->assertRedirect(route('suppliers.index'));
        $response->assertSessionHas('error');
    }

    /**
     * Test supplier edit with non-existent supplier.
     *
     * @return void
     */
    public function test_supplier_edit_with_non_existent_supplier(): void
    {
        $response = $this->get(route('suppliers.edit', 99999));

        // Controller redirects with error message instead of 404
        $response->assertStatus(302);
        $response->assertRedirect(route('suppliers.index'));
        $response->assertSessionHas('error');
    }

    /**
     * Test supplier update with non-existent supplier.
     *
     * @return void
     */
    public function test_supplier_update_with_non_existent_supplier(): void
    {
        $response = $this->patch(route('suppliers.update', 99999), [
            'name' => 'Test',
            'contact_person' => 'Test Contact',
            'phone' => '+31612345678',
            'email' => 'test@example.com',
            'address' => 'Test Address 123',
            'supplier_type' => 'Farmer',
        ]);

        // Controller redirects with error message instead of 404
        $response->assertStatus(302);
        $response->assertRedirect(route('suppliers.index'));
        $response->assertSessionHas('error');
    }

    /**
     * Test supplier index with order status filter.
     *
     * @return void
     */
    public function test_supplier_index_with_order_status_filter(): void
    {
        $supplierWithActiveOrders = Supplier::factory()->create();
        $supplierWithoutActiveOrders = Supplier::factory()->create();

        // Create active orders for first supplier
        $activeFoodStorage = FoodStorage::factory()->create(['status' => 'onderweg']);
        Produce::factory()->create([
            'supplier_id' => $supplierWithActiveOrders->id,
            'food_storage_id' => $activeFoodStorage->id
        ]);

        // Create delivered orders for second supplier
        $deliveredFoodStorage = FoodStorage::factory()->create(['status' => 'geleverd']);
        Produce::factory()->create([
            'supplier_id' => $supplierWithoutActiveOrders->id,
            'food_storage_id' => $deliveredFoodStorage->id
        ]);

        // Test filter for suppliers with active orders
        $response = $this->get(route('suppliers.index', ['order_status' => 'onderweg']));
        $response->assertStatus(200);
        $response->assertSee($supplierWithActiveOrders->name);
        $response->assertDontSee($supplierWithoutActiveOrders->name);

        // Test filter for suppliers without active orders (actief status)
        $response = $this->get(route('suppliers.index', ['order_status' => 'actief']));
        $response->assertStatus(200);
        $response->assertSee($supplierWithoutActiveOrders->name);
        $response->assertDontSee($supplierWithActiveOrders->name);
    }

    /**
     * Test supplier creation with duplicate name fails.
     *
     * @return void
     */
    public function test_supplier_creation_with_duplicate_name_fails(): void
    {
        $existingSupplier = Supplier::factory()->create([
            'name' => 'Duplicate Name'
        ]);

        $duplicateData = [
            'name' => 'Duplicate Name', // Same name
            'contact_person' => 'Different Person',
            'phone' => '+31612345678',
            'email' => 'different@email.com',
            'address' => 'Different Address 123',
            'supplier_type' => 'Supermarket',
        ];

        $response = $this->post(route('suppliers.store'), $duplicateData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Test supplier creation with duplicate email fails.
     *
     * @return void
     */
    public function test_supplier_creation_with_duplicate_email_fails(): void
    {
        $existingSupplier = Supplier::factory()->create([
            'email' => 'duplicate@email.com'
        ]);

        $duplicateData = [
            'name' => 'Different Name',
            'contact_person' => 'Different Person',
            'phone' => '+31612345678',
            'email' => 'duplicate@email.com', // Same email
            'address' => 'Different Address 123',
            'supplier_type' => 'Supermarket',
        ];

        $response = $this->post(route('suppliers.store'), $duplicateData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test supplier update allows same name/email for same supplier.
     *
     * @return void
     */
    public function test_supplier_update_allows_same_name_email_for_same_supplier(): void
    {
        $supplier = Supplier::factory()->create([
            'name' => 'Existing Name',
            'email' => 'existing@email.com'
        ]);

        $updateData = [
            'name' => 'Existing Name', // Same name, should be allowed
            'contact_person' => 'Updated Person',
            'phone' => '+31612345678',
            'email' => 'existing@email.com', // Same email, should be allowed
            'address' => 'Updated Address 123',
            'supplier_type' => 'Farmer',
            'is_actief' => true,
        ];

        $response = $this->patch(route('suppliers.update', $supplier), $updateData);

        $response->assertStatus(302);
        $response->assertRedirect(route('suppliers.show', $supplier));
        $response->assertSessionHasNoErrors();
    }

    /**
     * Test comprehensive supplier workflow.
     *
     * @return void
     */
    public function test_comprehensive_supplier_workflow(): void
    {
        // 1. Visit index page (should be empty)
        $response = $this->get(route('suppliers.index'));
        $response->assertStatus(200);

        // 2. Visit create page
        $response = $this->get(route('suppliers.create'));
        $response->assertStatus(200);

        // 3. Create a new supplier
        $supplierData = [
            'name' => 'Workflow Test Supplier',
            'contact_person' => 'Workflow Person',
            'phone' => '+31612345678',
            'email' => 'workflow@test.nl',
            'address' => 'Workflow Street 123',
            'supplier_type' => 'Wholesaler',
            'is_actief' => true,
            'opmerking' => 'Workflow test',
        ];

        $response = $this->post(route('suppliers.store'), $supplierData);
        $supplier = Supplier::where('email', 'workflow@test.nl')->first();
        $response->assertRedirect(route('suppliers.show', $supplier));

        // 4. View the created supplier
        $response = $this->get(route('suppliers.show', $supplier));
        $response->assertStatus(200);
        $response->assertSee('Workflow Test Supplier');

        // 5. Edit the supplier
        $response = $this->get(route('suppliers.edit', $supplier));
        $response->assertStatus(200);

        // 6. Update the supplier
        $updateData = array_merge($supplierData, [
            'name' => 'Updated Workflow Supplier',
            'is_actief' => false
        ]);

        $response = $this->patch(route('suppliers.update', $supplier), $updateData);
        $response->assertRedirect(route('suppliers.show', $supplier));

        // 7. Verify update
        $supplier->refresh();
        $this->assertEquals('Updated Workflow Supplier', $supplier->name);
        $this->assertFalse($supplier->is_actief);

        // 8. Delete the supplier
        $response = $this->delete(route('suppliers.destroy', $supplier));
        $response->assertRedirect(route('suppliers.index'));

        // 9. Verify deletion
        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }
}
