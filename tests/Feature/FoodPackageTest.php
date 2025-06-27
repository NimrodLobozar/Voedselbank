<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Produce;
use App\Models\FoodPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodPackageTest extends TestCase
{
    use RefreshDatabase;

    public function test_food_package_creation_and_stock_decrement()
    {
        $user = User::factory()->create();
        $produce = Produce::factory()->create(['amount' => 10]);

        $this->actingAs($user);

        $response = $this->post(route('food_packages.store'), [
            'customer_id' => 1,
            'package_name' => 'Testpakket',
            'assembled_at' => now()->toDateString(),
            'distribution_date' => now()->toDateString(),
            'produce' => [
                ['id' => $produce->id, 'quantity' => 5]
            ]
        ]);

        $response->assertRedirect(route('food_packages.index'));
        $this->assertDatabaseHas('food_package', ['package_name' => 'Testpakket']);
        $this->assertEquals(5, Produce::find($produce->id)->amount);
    }

    public function test_cannot_create_package_with_insufficient_stock()
    {
        $user = User::factory()->create();
        $produce = Produce::factory()->create(['amount' => 2]);

        $this->actingAs($user);

        $response = $this->post(route('food_packages.store'), [
            'customer_id' => 1,
            'package_name' => 'Testpakket',
            'assembled_at' => now()->toDateString(),
            'distribution_date' => now()->toDateString(),
            'produce' => [
                ['id' => $produce->id, 'quantity' => 5]
            ]
        ]);

        $response->assertSessionHasErrors('produce');
        $this->assertEquals(2, Produce::find($produce->id)->amount);
    }
}
