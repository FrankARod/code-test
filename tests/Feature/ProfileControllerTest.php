<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAttachProduct()
    {
        $valid = User::factory()->subscribed()->create();
        $invalid = User::factory()->expired()->create();
        $product = Product::factory()->create();

        $resp = $this->be($valid)->post(route('profile.attach-product'), ['product_id' => $product->id]);

        $resp->assertOk();
        $this->assertNotNull($valid->products()->find($product->id));

        $resp = $this->be($invalid)->post(route('profile.attach-product'), ['product_id' => $product->id]);
        $resp->assertStatus(403);
        $this->assertNull($invalid->products()->find($product->id));
    }

    public function testProducts() {
        $user = User::factory()->subscribed()->create();
        $products = Product::factory()->count(5)->create();
        $detachedProducts = Product::factory()->count(5)->create(); // products that aren't associated with user to test filtering
        $user->products()->attach($products);

        $response = $this->be($user)->getJson('/api/profile/products');
        $response->assertOK();
        $this->assertEquals($products->count(), count($response['products']['data']));
    }

    public function testDetachProduct() {
        $user = User::factory()->subscribed()->create();
        $product = Product::factory()->create();
        $user->products()->attach($product);

        $response = $this->be($user)->postJson(route('profile.detach-product'), ['product_id' => $product->id]);
        $response->assertOK();
        $this->assertNull($user->products()->find($product->id), 'Product should be detached from user.');
    }
}
