<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => auth()->user()->id,
            'amount' => $this->faker->biasedNumberBetween(10000 , 50000),
            'currency_key' => Currency::factory()->create()->first()->key,
        ];
    }
}
