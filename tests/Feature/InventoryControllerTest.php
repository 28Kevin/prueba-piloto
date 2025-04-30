<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Inventory;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_can_be_created()
    {
        $product = Product::factory()->create();

        $payload = [
            'product_id' => $product->id,
            'quantity' => 100,
        ];

        $response = $this->postJson('/api/v1-inventory-store', $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure(['data']);

        $this->assertDatabaseHas('inventories', [
            'product_id' => $product->id,
            'quantity' => 100,
        ]);
    }

    public function test_inventory_can_be_retrieved_by_product_id()
    {
        $product = Product::factory()->create();

        Inventory::create([
            'product_id' => $product->id,
            'quantity' => 30,
        ]);

        $response = $this->getJson("/api/v1-inventory-by-product/{$product->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'meta' => [
                         'message',
                         'data'
                     ]
                 ]);

        $this->assertEquals(
            30,
            $response->json('meta.data')[0]['quantity']
        );
    }
}
