<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FoodPackage
{
    public static function getTotalItems(array $items)
    {
        return array_sum(array_column($items, 'quantity'));
    }

    public static function create(array $data)
    {
        // Simulate creation by returning the data with an ID
        $data['id'] = rand(1, 1000);
        return $data;
    }
}

class FoodPackageTest extends TestCase
{
    public function test_basic_math_works()
    {
        $this->assertEquals(2, 1 + 1);
    }

    public function test_get_total_items_returns_sum_of_quantities()
    {
        $items = [
            ['name' => 'Apple', 'quantity' => 2],
            ['name' => 'Banana', 'quantity' => 3],
            ['name' => 'Carrot', 'quantity' => 5],
        ];
        $total = FoodPackage::getTotalItems($items);
        $this->assertEquals(10, $total);
    }

    public function test_food_package_can_be_created()
    {
        $data = [
            'name' => 'Test Package',
            'items' => [
                ['name' => 'Apple', 'quantity' => 2],
                ['name' => 'Banana', 'quantity' => 3],
            ],
        ];
        $package = FoodPackage::create($data);

        $this->assertArrayHasKey('id', $package);
        $this->assertEquals('Test Package', $package['name']);
        $this->assertCount(2, $package['items']);
    }
}