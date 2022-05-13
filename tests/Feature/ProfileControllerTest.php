<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;
use App\Models\User;
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
}
