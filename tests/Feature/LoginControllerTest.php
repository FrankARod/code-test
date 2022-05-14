<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testToken()
    {
        $email = $this->faker->email;
        $password = $this->faker->password();
        $tokenName = 'Test';
        
        $user = User::factory()->create(['email' => $email, 'password' => Hash::make($password)]);
        
        $response = $this->postJson('/api/token', [
            'email' => $email,
            'password' => $password,
            'name' => $tokenName
        ]);

        $response->assertOk();
        $this->assertNotNull($response['token']);

        $response = $this->postJson('/api/token', [
            'email' => $email,
            'password' => 'wrongpassword',
            'name' => $tokenName
        ]);

        $response->assertStatus(422);
        $response
            ->assertJson(function (AssertableJson $json) {
                $json
                    ->missing('token')->etc();
            });
    }
}
