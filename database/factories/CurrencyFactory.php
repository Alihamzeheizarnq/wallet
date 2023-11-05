<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Japanese Yen',
            'key' => 'jpy',
            'iso_code' => 'JPY',
            'symbol' => '¥',
            'user_id' => User::factory()->create()->first()->id,
        ];
    }
}
