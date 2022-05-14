<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class ProductControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function testStore() {
        $input = [
            'name' => $this->faker->name,
            'price' => $this->faker->randomFloat(2, 1, 6),
            'description' => $this->faker->sentence,
        ];
        
        // user must be authenticated to create products
        $response = $this->postJson('/api/products', $input);
        $response->assertStatus(401);
        
        // assert authenticated user with valid input can create products
        $user = User::factory()->create();
        $response = $this->be($user)->postJson('/api/products', $input);
        $response->assertOk();
        $response->assertJson(function (AssertableJson $json) {
            return $json->has('product_id');
        });
        
        // assert created product's fields match input
        $prodID = $response['product_id'];
        $product = Product::find($prodID);
        $this->assertNotNull($product);
        foreach ($input as $field => $value) {
            $this->assertEquals($value, $product->$field);
        }
    }

    public function testUpdate() {
        $product = Product::factory()->create();
        $input = [
            'name' => $this->faker->name,
            'price' => $this->faker->randomFloat(2, 1, 6),
            'description' => $this->faker->sentence,
        ];
        
        // user must be authenticated to update products
        $response = $this->putJson("/api/products/{$product->id}", $input);
        $response->assertStatus(401);
        
        // assert authenticated user with valid input can update products
        $user = User::factory()->create();
        $response = $this->be($user)->putJson("/api/products/{$product->id}", $input);
        $response->assertOk();
        
        // assert updated product's fields match input
        $updatedProduct = Product::find($product->id);
        foreach ($input as $field => $value) {
            $this->assertEquals($value, $updatedProduct->$field);
        }
    }

    public function testDestroy() {
        $product = Product::factory()->create();
        $response = $this->deleteJson("/api/products/{$product->id}");
        $response->assertStatus(401);
        $this->assertNotNull(Product::find($product->id), 'Product should not be deletable by guest users.');

        $user = User::factory()->create();
        $response = $this->be($user)->deleteJson("/api/products/{$product->id}");
        $response->assertOk();
        $this->assertNull(Product::find($product->id), 'Product should be deletable by authenticated users.');
    }
}
