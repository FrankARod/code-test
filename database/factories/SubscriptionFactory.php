<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'start_date' => now(),
            'end_date' => now()->addYear()
        ];
    }

    public function expired()
    {
        return $this->state(function (array $attributes) {
            return [
                'start_date' => now()->subYear(),
                'end_date' => now()->subDay(),
            ];
        });
    }
}
