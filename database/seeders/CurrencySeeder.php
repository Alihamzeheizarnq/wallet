<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory(1)->create()->first();

        $currencies = [
            [
                'name' => 'Japanese Yen',
                'key' => 'jpy',
                'iso_code' => 'JPY',
                'symbol' => '¥',
                'user_id' => $user->id,
            ],
            [
                'name' => 'United States Dollar',
                'key' => 'usd',
                'iso_code' => 'USD',
                'symbol' => '$',
                'user_id' => $user->id,
            ],
            [
                'name' => 'British Pound Sterling',
                'key' => 'gbp',
                'iso_code' => 'GBP',
                'symbol' => '£',
                'user_id' => $user->id,
            ],
            [
                'name' => 'Euro',
                'key' => 'eur',
                'iso_code' => 'EUR',
                'symbol' => '€',
                'user_id' => $user->id,
            ],
            [
                'name' => 'Iranian Rial',
                'key' => 'irr',
                'iso_code' => 'IRR',
                'symbol' => '﷼',
                'user_id' => $user->id,
            ],
            [
                'name' => 'United Arab Emirates Durham',
                'key' => 'aed',
                'iso_code' => 'AED',
                'symbol' => 'د.إ',
                'user_id' => $user->id,
            ],
            [
                'name' => 'Chinese Yuan',
                'key' => 'cny',
                'iso_code' => 'CNY',
                'symbol' => '¥',
                'user_id' => $user->id,
            ],
            [
                'name' => 'Russian Ruble',
                'key' => 'rub',
                'iso_code' => 'RUB',
                'symbol' => '₽',
                'user_id' => $user->id,
            ],
        ];

        Schema::disableForeignKeyConstraints();
        Currency::truncate();
        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
        Schema::enableForeignKeyConstraints();
    }
}
