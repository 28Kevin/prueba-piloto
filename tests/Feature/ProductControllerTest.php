<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Inventory;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_creation_with_inventory()
    {
        $payload = [
            'name' => 'Keyboard',
            'price' => 50,
            'quantity' => 20
        ];

        $response = $this->postJson('/api/v1-product-store', $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure(['data']);

        $this->assertDatabaseHas('products', ['name' => 'Keyboard']);
        $this->assertDatabaseHas('inventories', ['quantity' => 20]);
    }

    public function test_product_purchase_updates_inventory()
    {
        $product = Product::factory()->create();

        Inventory::create([
            'product_id' => $product->id,
            'quantity' => 10
        ]);

        $payload = [
            'product_id' => $product->id,
            'quantity' => 3,
            'total' => 150,
        ];

        $response = $this->postJson('/api/v1-buy-product', $payload);

        $response->assertStatus(201);

        $this->assertEquals(
            7,
            Inventory::where('product_id', $product->id)->first()->quantity
        );
    }
}
