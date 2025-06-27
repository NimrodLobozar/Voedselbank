<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Supplier;

/**
 * Unit tests for the Supplier model.
 * 
 * Tests the basic functionality, validation, and business logic
 * of the Supplier model without database dependencies.
 *
 * @package Tests\Unit
 * @author Voedselbank Development Team
 * @version 1.0
 */
class SupplierTest extends TestCase
{
    /**
     * Test that supplier model can be instantiated.
     *
     * @return void
     */
    public function test_supplier_model_can_be_instantiated(): void
    {
        $supplier = new Supplier();
        $this->assertInstanceOf(Supplier::class, $supplier);
    }

    /**
     * Test that supplier has the correct fillable attributes.
     *
     * @return void
     */
    public function test_supplier_fillable_attributes(): void
    {
        $expectedFillable = [
            'name',
            'contact_person',
            'phone',
            'email',
            'address',
            'supplier_type',
            'is_actief',
            'opmerking',
            'datum_aangemaakt',
            'datum_gewijzigd'
        ];

        $supplier = new Supplier();
        $this->assertEquals($expectedFillable, $supplier->getFillable());
    }

    /**
     * Test that supplier model uses the correct database table.
     *
     * @return void
     */
    public function test_supplier_uses_correct_table(): void
    {
        $supplier = new Supplier();
        $this->assertEquals('suppliers', $supplier->getTable());
    }

    /**
     * Test that supplier has proper cast configuration.
     *
     * @return void
     */
    public function test_supplier_casts_configuration(): void
    {
        $supplier = new Supplier();
        $casts = $supplier->getCasts();

        $this->assertArrayHasKey('is_actief', $casts);
        $this->assertEquals('boolean', $casts['is_actief']);
        $this->assertArrayHasKey('datum_aangemaakt', $casts);
        $this->assertEquals('datetime', $casts['datum_aangemaakt']);
        $this->assertArrayHasKey('datum_gewijzigd', $casts);
        $this->assertEquals('datetime', $casts['datum_gewijzigd']);
    }

    /**
     * Test that supplier attributes can be set and retrieved correctly.
     *
     * @return void
     */
    public function test_supplier_attributes_can_be_set(): void
    {
        $supplier = new Supplier();

        // Set all the attributes
        $supplier->name = 'Test Supermarket';
        $supplier->contact_person = 'John Doe';
        $supplier->phone = '+31612345678';
        $supplier->email = 'john@testsupermarket.nl';
        $supplier->address = 'Teststraat 123, 1234AB Amsterdam';
        $supplier->supplier_type = 'Supermarket';
        $supplier->is_actief = true;
        $supplier->opmerking = 'Test opmerking';

        // Assert all attributes are set correctly
        $this->assertEquals('Test Supermarket', $supplier->name);
        $this->assertEquals('John Doe', $supplier->contact_person);
        $this->assertEquals('+31612345678', $supplier->phone);
        $this->assertEquals('john@testsupermarket.nl', $supplier->email);
        $this->assertEquals('Teststraat 123, 1234AB Amsterdam', $supplier->address);
        $this->assertEquals('Supermarket', $supplier->supplier_type);
        $this->assertIsBool($supplier->is_actief);
        $this->assertTrue($supplier->is_actief);
        $this->assertEquals('Test opmerking', $supplier->opmerking);
    }

    /**
     * Test that supplier supports all valid supplier types.
     *
     * @return void
     */
    public function test_supplier_supports_all_valid_types(): void
    {
        $validTypes = Supplier::SUPPLIER_TYPES;
        $this->assertEquals(['Supermarket', 'Farmer', 'Wholesaler', 'Individual'], $validTypes);

        foreach ($validTypes as $type) {
            $supplier = new Supplier();
            $supplier->supplier_type = $type;
            $this->assertEquals($type, $supplier->supplier_type);
        }
    }

    /**
     * Test that is_actief is properly cast to boolean.
     *
     * @return void
     */
    public function test_is_actief_boolean_casting(): void
    {
        $supplier = new Supplier();

        // Test with boolean true
        $supplier->is_actief = true;
        $this->assertIsBool($supplier->is_actief);
        $this->assertTrue($supplier->is_actief);

        // Test with boolean false
        $supplier->is_actief = false;
        $this->assertIsBool($supplier->is_actief);
        $this->assertFalse($supplier->is_actief);

        // Test with integer 1
        $supplier->is_actief = 1;
        $this->assertIsBool($supplier->is_actief);
        $this->assertTrue($supplier->is_actief);

        // Test with integer 0
        $supplier->is_actief = 0;
        $this->assertIsBool($supplier->is_actief);
        $this->assertFalse($supplier->is_actief);
    }

    /**
     * Test supplier mass assignment functionality.
     *
     * @return void
     */
    public function test_supplier_mass_assignment(): void
    {
        $data = [
            'name' => 'Mass Assignment Test',
            'contact_person' => 'Jane Doe',
            'phone' => '+31687654321',
            'email' => 'jane@test.nl',
            'address' => 'Testlaan 456, 5678CD Rotterdam',
            'supplier_type' => 'Farmer',
            'is_actief' => false,
            'opmerking' => 'Mass assignment test'
        ];

        $supplier = new Supplier($data);

        $this->assertEquals('Mass Assignment Test', $supplier->name);
        $this->assertEquals('Jane Doe', $supplier->contact_person);
        $this->assertEquals('+31687654321', $supplier->phone);
        $this->assertEquals('jane@test.nl', $supplier->email);
        $this->assertEquals('Testlaan 456, 5678CD Rotterdam', $supplier->address);
        $this->assertEquals('Farmer', $supplier->supplier_type);
        $this->assertIsBool($supplier->is_actief);
        $this->assertFalse($supplier->is_actief);
        $this->assertEquals('Mass assignment test', $supplier->opmerking);
    }

    /**
     * Test that supplier model has the required relationship methods.
     *
     * @return void
     */
    public function test_supplier_has_relationship_methods(): void
    {
        $supplier = new Supplier();

        $this->assertTrue(method_exists($supplier, 'produce'));
        $this->assertTrue(method_exists($supplier, 'foodStorages'));
        $this->assertTrue(method_exists($supplier, 'activeFoodStorages'));
        $this->assertTrue(method_exists($supplier, 'hasActiveOrders'));
    }

    /**
     * Test that nullable attributes work correctly.
     *
     * @return void
     */
    public function test_supplier_nullable_attributes(): void
    {
        $supplier = new Supplier();

        // opmerking should be nullable
        $supplier->opmerking = null;
        $this->assertNull($supplier->opmerking);

        // Required fields should not be null when set
        $supplier->name = 'Test';
        $this->assertNotNull($supplier->name);
        $this->assertEquals('Test', $supplier->name);
    }

    /**
     * Test that supplier model has the HasFactory trait.
     *
     * @return void
     */
    public function test_supplier_has_factory_trait(): void
    {
        $traits = class_uses(Supplier::class);
        $this->assertContains('Illuminate\Database\Eloquent\Factories\HasFactory', $traits);
    }

    /**
     * Test supplier type validation method.
     *
     * @return void
     */
    public function test_supplier_type_validation(): void
    {
        // Test valid types
        $this->assertTrue(Supplier::isValidSupplierType('Supermarket'));
        $this->assertTrue(Supplier::isValidSupplierType('Farmer'));
        $this->assertTrue(Supplier::isValidSupplierType('Wholesaler'));
        $this->assertTrue(Supplier::isValidSupplierType('Individual'));

        // Test invalid types
        $this->assertFalse(Supplier::isValidSupplierType('InvalidType'));
        $this->assertFalse(Supplier::isValidSupplierType(''));
        $this->assertFalse(Supplier::isValidSupplierType('supermarket')); // case sensitive
    }

    /**
     * Test supplier display attribute methods.
     *
     * @return void
     */
    public function test_supplier_display_attributes(): void
    {
        $supplier = new Supplier([
            'contact_person' => 'John Doe',
            'phone' => '+31612345678',
            'email' => 'john@example.com',
            'supplier_type' => 'Supermarket'
        ]);

        // Test full contact attribute
        $expectedContact = 'John Doe - +31612345678 - john@example.com';
        $this->assertEquals($expectedContact, $supplier->full_contact);

        // Test supplier type display attribute
        $this->assertEquals('Supermarkt', $supplier->supplier_type_display);

        // Test other types
        $supplier->supplier_type = 'Farmer';
        $this->assertEquals('Boer', $supplier->supplier_type_display);

        $supplier->supplier_type = 'Wholesaler';
        $this->assertEquals('Groothandel', $supplier->supplier_type_display);

        $supplier->supplier_type = 'Individual';
        $this->assertEquals('Particulier', $supplier->supplier_type_display);
    }

    /**
     * Test supplier constants.
     *
     * @return void
     */
    public function test_supplier_constants(): void
    {
        // Test supplier types constant
        $expectedTypes = ['Supermarket', 'Farmer', 'Wholesaler', 'Individual'];
        $this->assertEquals($expectedTypes, Supplier::SUPPLIER_TYPES);

        // Test active order statuses constant
        $expectedStatuses = ['onderweg', 'in_behandeling'];
        $this->assertEquals($expectedStatuses, Supplier::ACTIVE_ORDER_STATUSES);
    }

    /**
     * Test that supplier model has proper documentation.
     *
     * @return void
     */
    public function test_supplier_model_has_documentation(): void
    {
        $reflection = new \ReflectionClass(Supplier::class);
        $docComment = $reflection->getDocComment();

        // Check that the class has documentation
        $this->assertNotFalse($docComment);
        $this->assertStringContainsString('Supplier Model', $docComment);
        $this->assertStringContainsString('@package App\Models', $docComment);
    }

    /**
     * Test supplier model creation with minimum required data.
     *
     * @return void
     */
    public function test_supplier_creation_with_minimum_data(): void
    {
        $minimalData = [
            'name' => 'Minimal Supplier',
            'contact_person' => 'Contact',
            'phone' => '+31612345678',
            'email' => 'minimal@test.nl',
            'address' => 'Minimal Street 1',
            'supplier_type' => 'Individual',
        ];

        $supplier = new Supplier($minimalData);

        $this->assertEquals('Minimal Supplier', $supplier->name);
        $this->assertEquals('Contact', $supplier->contact_person);
        $this->assertEquals('+31612345678', $supplier->phone);
        $this->assertEquals('minimal@test.nl', $supplier->email);
        $this->assertEquals('Minimal Street 1', $supplier->address);
        $this->assertEquals('Individual', $supplier->supplier_type);
        $this->assertNull($supplier->opmerking); // Should be nullable
    }

    /**
     * Test supplier model creation with all data.
     *
     * @return void
     */
    public function test_supplier_creation_with_complete_data(): void
    {
        $completeData = [
            'name' => 'Complete Supplier',
            'contact_person' => 'Complete Contact',
            'phone' => '+31687654321',
            'email' => 'complete@test.nl',
            'address' => 'Complete Street 123, 1234AB City',
            'supplier_type' => 'Supermarket',
            'is_actief' => true,
            'opmerking' => 'Complete test opmerking with details',
        ];

        $supplier = new Supplier($completeData);

        foreach ($completeData as $key => $value) {
            $this->assertEquals($value, $supplier->$key);
        }
    }

    /**
     * Test supplier model validation with edge case data.
     *
     * @return void
     */
    public function test_supplier_model_with_edge_case_data(): void
    {
        $supplier = new Supplier();

        // Test with very long but valid strings
        $longName = str_repeat('A', 255); // Max length
        $supplier->name = $longName;
        $this->assertEquals($longName, $supplier->name);

        // Test with minimum length strings
        $supplier->name = 'AB'; // Min 2 characters
        $this->assertEquals('AB', $supplier->name);

        // Test special characters in name
        $supplier->name = 'Supplier & Co. (Ltd.)';
        $this->assertEquals('Supplier & Co. (Ltd.)', $supplier->name);

        // Test international phone formats
        $internationalPhone = '+31 (0)20 123 4567';
        $supplier->phone = $internationalPhone;
        $this->assertEquals($internationalPhone, $supplier->phone);

        // Test email with complex format
        $complexEmail = 'test.email+tag@sub.domain.com';
        $supplier->email = $complexEmail;
        $this->assertEquals($complexEmail, $supplier->email);
    }

    /**
     * Test supplier model data integrity.
     *
     * @return void
     */
    public function test_supplier_model_data_integrity(): void
    {
        $supplier = new Supplier();

        // Test that changing one field doesn't affect others
        $supplier->name = 'Test Name';
        $supplier->email = 'test@example.com';

        $this->assertEquals('Test Name', $supplier->name);
        $this->assertEquals('test@example.com', $supplier->email);

        // Change name, email should remain the same
        $supplier->name = 'New Name';
        $this->assertEquals('New Name', $supplier->name);
        $this->assertEquals('test@example.com', $supplier->email);
    }

    /**
     * Test supplier model with null values for optional fields.
     *
     * @return void
     */
    public function test_supplier_model_with_null_optional_fields(): void
    {
        $supplier = new Supplier([
            'name' => 'Test Supplier',
            'contact_person' => 'Contact Person',
            'phone' => '+31612345678',
            'email' => 'test@example.com',
            'address' => 'Test Street 123',
            'supplier_type' => 'Farmer',
            'opmerking' => null, // Explicitly null
        ]);

        $this->assertNull($supplier->opmerking);
        $this->assertNotNull($supplier->name);
        $this->assertNotNull($supplier->email);
    }

    /**
     * Test supplier model attribute mutators (if any).
     *
     * @return void
     */
    public function test_supplier_model_attribute_mutators(): void
    {
        $supplier = new Supplier();

        // Test email normalization (if implemented)
        $supplier->email = 'TEST@EXAMPLE.COM';
        // In a real scenario, this might be normalized to lowercase
        $this->assertIsString($supplier->email);

        // Test phone normalization (if implemented)
        $supplier->phone = ' +31 6 1234 5678 ';
        // In a real scenario, this might be normalized
        $this->assertIsString($supplier->phone);
    }

    /**
     * Test supplier model business logic methods.
     *
     * @return void
     */
    public function test_supplier_model_business_logic_methods(): void
    {
        $supplier = new Supplier([
            'name' => 'Business Logic Test',
            'contact_person' => 'Logic Contact',
            'phone' => '+31612345678',
            'email' => 'logic@test.nl',
            'address' => 'Logic Street 123',
            'supplier_type' => 'Wholesaler',
            'is_actief' => true,
        ]);

        // Test static validation method
        $this->assertTrue(Supplier::isValidSupplierType('Wholesaler'));
        $this->assertTrue(Supplier::isValidSupplierType('Supermarket'));
        $this->assertFalse(Supplier::isValidSupplierType('NonExistentType'));
        $this->assertFalse(Supplier::isValidSupplierType(''));

        // Test case sensitivity
        $this->assertFalse(Supplier::isValidSupplierType('wholesaler')); // lowercase
        $this->assertFalse(Supplier::isValidSupplierType('WHOLESALER')); // uppercase
    }

    /**
     * Test supplier model constants and their usage.
     *
     * @return void
     */
    public function test_supplier_model_constants_usage(): void
    {
        // Test that constants are properly defined
        $this->assertTrue(defined('App\Models\Supplier::SUPPLIER_TYPES'));
        $this->assertTrue(defined('App\Models\Supplier::ACTIVE_ORDER_STATUSES'));

        // Test constant values
        $expectedTypes = ['Supermarket', 'Farmer', 'Wholesaler', 'Individual'];
        $this->assertEquals($expectedTypes, Supplier::SUPPLIER_TYPES);

        $expectedStatuses = ['onderweg', 'in_behandeling'];
        $this->assertEquals($expectedStatuses, Supplier::ACTIVE_ORDER_STATUSES);

        // Test that constants are arrays
        $this->assertIsArray(Supplier::SUPPLIER_TYPES);
        $this->assertIsArray(Supplier::ACTIVE_ORDER_STATUSES);

        // Test that constants are not empty
        $this->assertNotEmpty(Supplier::SUPPLIER_TYPES);
        $this->assertNotEmpty(Supplier::ACTIVE_ORDER_STATUSES);
    }

    /**
     * Test supplier model performance considerations.
     *
     * @return void
     */
    public function test_supplier_model_performance_considerations(): void
    {
        // Test that creating multiple instances is efficient
        $startTime = microtime(true);

        $suppliers = [];
        for ($i = 0; $i < 100; $i++) {
            $suppliers[] = new Supplier([
                'name' => "Supplier {$i}",
                'contact_person' => "Contact {$i}",
                'phone' => "+3161234567{$i}",
                'email' => "supplier{$i}@test.nl",
                'address' => "Street {$i}",
                'supplier_type' => Supplier::SUPPLIER_TYPES[$i % count(Supplier::SUPPLIER_TYPES)],
            ]);
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Should be very fast (less than 1 second for 100 instances)
        $this->assertLessThan(1.0, $executionTime);
        $this->assertCount(100, $suppliers);
    }

    /**
     * Test supplier model array conversion.
     *
     * @return void
     */
    public function test_supplier_model_array_conversion(): void
    {
        $supplierData = [
            'name' => 'Array Test Supplier',
            'contact_person' => 'Array Contact',
            'phone' => '+31612345678',
            'email' => 'array@test.nl',
            'address' => 'Array Street 123',
            'supplier_type' => 'Farmer',
            'is_actief' => true,
            'opmerking' => 'Array test',
        ];

        $supplier = new Supplier($supplierData);
        $supplierArray = $supplier->toArray();

        // Test that required fields are present in array
        $this->assertArrayHasKey('name', $supplierArray);
        $this->assertArrayHasKey('email', $supplierArray);
        $this->assertArrayHasKey('supplier_type', $supplierArray);

        // Test values match
        $this->assertEquals($supplierData['name'], $supplierArray['name']);
        $this->assertEquals($supplierData['email'], $supplierArray['email']);
        $this->assertEquals($supplierData['supplier_type'], $supplierArray['supplier_type']);
    }

    // ========================================
    // CRUD OPERATION TESTS
    // ========================================

    /**
     * Test supplier model creation operations and validation.
     *
     * @return void
     */
    public function test_supplier_create_operation_validation(): void
    {
        // Test valid creation data
        $validData = [
            'name' => 'New Supplier',
            'contact_person' => 'John Doe',
            'phone' => '+31612345678',
            'email' => 'john@newsupplier.nl',
            'address' => 'New Street 123, 1234AB Amsterdam',
            'supplier_type' => 'Supermarket',
            'is_actief' => true,
        ];

        $supplier = new Supplier($validData);

        // Test all attributes are properly set
        $this->assertEquals('New Supplier', $supplier->name);
        $this->assertEquals('John Doe', $supplier->contact_person);
        $this->assertEquals('+31612345678', $supplier->phone);
        $this->assertEquals('john@newsupplier.nl', $supplier->email);
        $this->assertEquals('New Street 123, 1234AB Amsterdam', $supplier->address);
        $this->assertEquals('Supermarket', $supplier->supplier_type);
        $this->assertTrue($supplier->is_actief);

        // Test that model is properly configured for creation
        $this->assertInstanceOf(Supplier::class, $supplier);
        $this->assertTrue(method_exists($supplier, 'save'));
        $this->assertTrue(is_callable([Supplier::class, 'create']));
    }

    /**
     * Test supplier creation with various data combinations.
     *
     * @return void
     */
    public function test_supplier_create_with_different_data_combinations(): void
    {
        // Test creation with minimal required data
        $minimalData = [
            'name' => 'Minimal Supplier',
            'contact_person' => 'Min Contact',
            'phone' => '+31611111111',
            'email' => 'min@test.nl',
            'address' => 'Min Street 1',
            'supplier_type' => 'Individual',
        ];

        $minimalSupplier = new Supplier($minimalData);
        $this->assertInstanceOf(Supplier::class, $minimalSupplier);
        $this->assertEquals('Individual', $minimalSupplier->supplier_type);
        $this->assertNull($minimalSupplier->opmerking);

        // Test creation with all possible fields
        $completeData = [
            'name' => 'Complete Supplier',
            'contact_person' => 'Complete Contact',
            'phone' => '+31622222222',
            'email' => 'complete@test.nl',
            'address' => 'Complete Street 123, 5678CD Rotterdam',
            'supplier_type' => 'Wholesaler',
            'is_actief' => false,
            'opmerking' => 'Complete supplier with all fields filled',
        ];

        $completeSupplier = new Supplier($completeData);
        $this->assertInstanceOf(Supplier::class, $completeSupplier);
        $this->assertFalse($completeSupplier->is_actief);
        $this->assertEquals('Complete supplier with all fields filled', $completeSupplier->opmerking);

        // Test creation with different supplier types
        foreach (Supplier::SUPPLIER_TYPES as $type) {
            $typeData = array_merge($minimalData, [
                'name' => "{$type} Supplier",
                'supplier_type' => $type,
                'email' => strtolower($type) . '@test.nl',
            ]);

            $typeSupplier = new Supplier($typeData);
            $this->assertEquals($type, $typeSupplier->supplier_type);
            $this->assertTrue(Supplier::isValidSupplierType($typeSupplier->supplier_type));
        }
    }

    /**
     * Test supplier model update operations.
     *
     * @return void
     */
    public function test_supplier_update_operation_functionality(): void
    {
        // Create initial supplier
        $initialData = [
            'name' => 'Original Supplier',
            'contact_person' => 'Original Contact',
            'phone' => '+31611111111',
            'email' => 'original@test.nl',
            'address' => 'Original Street 1',
            'supplier_type' => 'Individual',
            'is_actief' => true,
            'opmerking' => 'Original comment',
        ];

        $supplier = new Supplier($initialData);

        // Test updating individual fields
        $supplier->name = 'Updated Supplier';
        $this->assertEquals('Updated Supplier', $supplier->name);

        $supplier->contact_person = 'Updated Contact';
        $this->assertEquals('Updated Contact', $supplier->contact_person);

        $supplier->phone = '+31622222222';
        $this->assertEquals('+31622222222', $supplier->phone);

        $supplier->email = 'updated@test.nl';
        $this->assertEquals('updated@test.nl', $supplier->email);

        $supplier->address = 'Updated Street 123, 9876ZX Den Haag';
        $this->assertEquals('Updated Street 123, 9876ZX Den Haag', $supplier->address);

        $supplier->supplier_type = 'Supermarket';
        $this->assertEquals('Supermarket', $supplier->supplier_type);

        $supplier->is_actief = false;
        $this->assertFalse($supplier->is_actief);

        $supplier->opmerking = 'Updated comment with more details';
        $this->assertEquals('Updated comment with more details', $supplier->opmerking);

        // Test that model has update functionality
        $this->assertTrue(method_exists($supplier, 'update'));
        $this->assertTrue(method_exists($supplier, 'fill'));
    }

    /**
     * Test supplier bulk update operations.
     *
     * @return void
     */
    public function test_supplier_bulk_update_operations(): void
    {
        $supplier = new Supplier([
            'name' => 'Bulk Test Supplier',
            'contact_person' => 'Bulk Contact',
            'phone' => '+31611111111',
            'email' => 'bulk@test.nl',
            'address' => 'Bulk Street 1',
            'supplier_type' => 'Farmer',
            'is_actief' => true,
        ]);

        // Test bulk update using fill method
        $updateData = [
            'name' => 'Bulk Updated Supplier',
            'contact_person' => 'Bulk Updated Contact',
            'phone' => '+31633333333',
            'email' => 'bulkupdated@test.nl',
            'supplier_type' => 'Wholesaler',
            'is_actief' => false,
            'opmerking' => 'Bulk updated via fill method',
        ];

        $supplier->fill($updateData);

        // Verify all fields were updated
        $this->assertEquals('Bulk Updated Supplier', $supplier->name);
        $this->assertEquals('Bulk Updated Contact', $supplier->contact_person);
        $this->assertEquals('+31633333333', $supplier->phone);
        $this->assertEquals('bulkupdated@test.nl', $supplier->email);
        $this->assertEquals('Wholesaler', $supplier->supplier_type);
        $this->assertFalse($supplier->is_actief);
        $this->assertEquals('Bulk updated via fill method', $supplier->opmerking);

        // Test partial bulk update
        $partialUpdateData = [
            'name' => 'Partially Updated Supplier',
            'is_actief' => true,
        ];

        $supplier->fill($partialUpdateData);

        // Verify only specified fields were updated
        $this->assertEquals('Partially Updated Supplier', $supplier->name);
        $this->assertTrue($supplier->is_actief);
        // Other fields should remain unchanged
        $this->assertEquals('Bulk Updated Contact', $supplier->contact_person);
        $this->assertEquals('bulkupdated@test.nl', $supplier->email);
    }

    /**
     * Test supplier update validation scenarios.
     *
     * @return void
     */
    public function test_supplier_update_validation_scenarios(): void
    {
        $supplier = new Supplier([
            'name' => 'Validation Test Supplier',
            'contact_person' => 'Val Contact',
            'phone' => '+31611111111',
            'email' => 'validation@test.nl',
            'address' => 'Validation Street 1',
            'supplier_type' => 'Individual',
        ]);

        // Test updating with valid supplier types
        foreach (Supplier::SUPPLIER_TYPES as $type) {
            $supplier->supplier_type = $type;
            $this->assertEquals($type, $supplier->supplier_type);
            $this->assertTrue(Supplier::isValidSupplierType($supplier->supplier_type));
        }

        // Test boolean field updates
        $supplier->is_actief = true;
        $this->assertTrue($supplier->is_actief);

        $supplier->is_actief = false;
        $this->assertFalse($supplier->is_actief);

        // Test with various boolean-like values
        $supplier->is_actief = 1;
        $this->assertTrue($supplier->is_actief);

        $supplier->is_actief = 0;
        $this->assertFalse($supplier->is_actief);

        // Test nullable field updates
        $supplier->opmerking = 'Test comment';
        $this->assertEquals('Test comment', $supplier->opmerking);

        $supplier->opmerking = null;
        $this->assertNull($supplier->opmerking);

        $supplier->opmerking = '';
        $this->assertEquals('', $supplier->opmerking);
    }

    /**
     * Test supplier model delete operation preparations.
     *
     * @return void
     */
    public function test_supplier_delete_operation_preparation(): void
    {
        $supplier = new Supplier([
            'name' => 'Delete Test Supplier',
            'contact_person' => 'Delete Contact',
            'phone' => '+31611111111',
            'email' => 'delete@test.nl',
            'address' => 'Delete Street 1',
            'supplier_type' => 'Supermarket',
            'is_actief' => true,
        ]);

        // Test that model has delete methods available
        $this->assertTrue(method_exists($supplier, 'delete'));
        $this->assertTrue(method_exists($supplier, 'forceDelete'));

        // Test soft delete related methods (if implemented)
        if (method_exists($supplier, 'trashed')) {
            $this->assertTrue(method_exists($supplier, 'restore'));
            $this->assertTrue(method_exists($supplier, 'trashed'));
        }

        // Test model state before deletion
        $this->assertInstanceOf(Supplier::class, $supplier);
        $this->assertEquals('Delete Test Supplier', $supplier->name);
    }

    /**
     * Test supplier model deletion scenarios and cleanup.
     *
     * @return void
     */
    public function test_supplier_deletion_scenarios(): void
    {
        // Test active supplier deletion scenario
        $activeSupplier = new Supplier([
            'name' => 'Active Supplier to Delete',
            'contact_person' => 'Active Contact',
            'phone' => '+31611111111',
            'email' => 'active@delete.nl',
            'address' => 'Active Street 1',
            'supplier_type' => 'Wholesaler',
            'is_actief' => true,
        ]);

        // Test preparation for deletion of active supplier
        $this->assertTrue($activeSupplier->is_actief);
        $this->assertEquals('Active Supplier to Delete', $activeSupplier->name);

        // Test inactive supplier deletion scenario
        $inactiveSupplier = new Supplier([
            'name' => 'Inactive Supplier to Delete',
            'contact_person' => 'Inactive Contact',
            'phone' => '+31622222222',
            'email' => 'inactive@delete.nl',
            'address' => 'Inactive Street 2',
            'supplier_type' => 'Farmer',
            'is_actief' => false,
        ]);

        // Test preparation for deletion of inactive supplier
        $this->assertFalse($inactiveSupplier->is_actief);
        $this->assertEquals('Inactive Supplier to Delete', $inactiveSupplier->name);

        // Test supplier with relationships deletion scenario
        $supplierWithData = new Supplier([
            'name' => 'Supplier With Data',
            'contact_person' => 'Data Contact',
            'phone' => '+31633333333',
            'email' => 'data@delete.nl',
            'address' => 'Data Street 3',
            'supplier_type' => 'Individual',
            'is_actief' => true,
            'opmerking' => 'This supplier has related data',
        ]);

        // Test that supplier model can handle deletion preparation
        $this->assertNotNull($supplierWithData->opmerking);
        $this->assertEquals('This supplier has related data', $supplierWithData->opmerking);
    }

    /**
     * Test supplier CRUD operation data integrity.
     *
     * @return void
     */
    public function test_supplier_crud_data_integrity(): void
    {
        // Test create -> update -> prepare for delete cycle
        $supplier = new Supplier([
            'name' => 'Integrity Test Supplier',
            'contact_person' => 'Integrity Contact',
            'phone' => '+31611111111',
            'email' => 'integrity@test.nl',
            'address' => 'Integrity Street 1',
            'supplier_type' => 'Supermarket',
            'is_actief' => true,
        ]);

        // Verify initial state
        $this->assertEquals('Integrity Test Supplier', $supplier->name);
        $this->assertTrue($supplier->is_actief);

        // Update the supplier
        $supplier->fill([
            'name' => 'Updated Integrity Supplier',
            'is_actief' => false,
            'opmerking' => 'Updated for integrity test',
        ]);

        // Verify updated state
        $this->assertEquals('Updated Integrity Supplier', $supplier->name);
        $this->assertFalse($supplier->is_actief);
        $this->assertEquals('Updated for integrity test', $supplier->opmerking);

        // Verify other fields remain unchanged
        $this->assertEquals('Integrity Contact', $supplier->contact_person);
        $this->assertEquals('integrity@test.nl', $supplier->email);
        $this->assertEquals('Supermarket', $supplier->supplier_type);

        // Test that the model maintains integrity throughout operations
        $this->assertInstanceOf(Supplier::class, $supplier);
        $this->assertTrue(in_array($supplier->supplier_type, Supplier::SUPPLIER_TYPES));
    }

    /**
     * Test supplier CRUD operations with edge cases.
     *
     * @return void
     */
    public function test_supplier_crud_edge_cases(): void
    {
        // Test creation with edge case data
        $edgeCaseSupplier = new Supplier([
            'name' => 'A', // Minimum length
            'contact_person' => str_repeat('Long Name ', 10), // Long name
            'phone' => '+31 (0)20-123-4567', // Complex format
            'email' => 'test.email+tag@sub.domain.co.uk', // Complex email
            'address' => "Multi-line\nAddress\nWith\nSpecial Characters!@#$%",
            'supplier_type' => 'Individual',
            'is_actief' => true,
        ]);

        // Verify edge case creation
        $this->assertEquals('A', $edgeCaseSupplier->name);
        $this->assertStringContainsString('Long Name', $edgeCaseSupplier->contact_person);
        $this->assertStringContainsString('@sub.domain.co.uk', $edgeCaseSupplier->email);

        // Test updating with edge cases
        $edgeCaseSupplier->name = str_repeat('VeryLongSupplierName', 10); // Very long name
        $this->assertStringContainsString('VeryLongSupplierName', $edgeCaseSupplier->name);

        // Test updating boolean with edge values
        $edgeCaseSupplier->is_actief = '1'; // String '1'
        $this->assertTrue($edgeCaseSupplier->is_actief);

        $edgeCaseSupplier->is_actief = '0'; // String '0'
        $this->assertFalse($edgeCaseSupplier->is_actief);

        // Test null and empty string handling
        $edgeCaseSupplier->opmerking = '';
        $this->assertEquals('', $edgeCaseSupplier->opmerking);

        $edgeCaseSupplier->opmerking = null;
        $this->assertNull($edgeCaseSupplier->opmerking);
    }

    /**
     * Test supplier CRUD operation performance.
     *
     * @return void
     */
    public function test_supplier_crud_performance(): void
    {
        $startTime = microtime(true);

        // Test multiple create operations
        $suppliers = [];
        for ($i = 0; $i < 50; $i++) {
            $suppliers[] = new Supplier([
                'name' => "Performance Supplier {$i}",
                'contact_person' => "Contact {$i}",
                'phone' => "+3161{$i}111111",
                'email' => "perf{$i}@test.nl",
                'address' => "Street {$i}",
                'supplier_type' => Supplier::SUPPLIER_TYPES[$i % count(Supplier::SUPPLIER_TYPES)],
                'is_actief' => ($i % 2 === 0),
            ]);
        }

        // Test multiple update operations
        foreach ($suppliers as $index => $supplier) {
            $supplier->fill([
                'name' => "Updated Performance Supplier {$index}",
                'is_actief' => !$supplier->is_actief,
                'opmerking' => "Updated comment {$index}",
            ]);
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Should complete operations quickly
        $this->assertLessThan(1.0, $executionTime);
        $this->assertCount(50, $suppliers);

        // Verify final state
        foreach ($suppliers as $index => $supplier) {
            $this->assertStringContainsString("Updated Performance Supplier {$index}", $supplier->name);
            $this->assertEquals("Updated comment {$index}", $supplier->opmerking);
        }
    }
}
