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
                'abbr' => 'JPY',
                'symbol' => '¥',
                'user_id' => $user->id,
            ],
            [
                'name' => 'United States Dollar',
                'key' => 'usd',
                'abbr' => 'USD',
                'symbol' => '$',
                'user_id' => $user->id,
            ],
            [
                'name' => 'British Pound Sterling',
                'key' => 'gbp',
                'abbr' => 'GBP',
                'symbol' => '£',
                'user_id' => $user->id,
            ],
            [
                'name' => 'Euro',
                'key' => 'eur',
                'abbr' => 'EUR',
                'symbol' => '€',
                'user_id' => $user->id,
            ],
            [
                'name' => 'Iranian Rial',
                'key' => 'irr',
                'abbr' => 'IRR',
                'symbol' => '﷼',
                'user_id' => $user->id,
            ],
            [
                'name' => 'United Arab Emirates Durham',
                'key' => 'aed',
                'abbr' => 'AED',
                'symbol' => 'د.إ',
                'user_id' => $user->id,
            ],
            [
                'name' => 'Chinese Yuan',
                'key' => 'cny',
                'abbr' => 'CNY',
                'symbol' => '¥',
                'user_id' => $user->id,
            ],
            [
                'name' => 'Russian Ruble',
                'key' => 'rub',
                'abbr' => 'RUB',
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
