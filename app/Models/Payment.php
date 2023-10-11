<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @throws \Exception
     */
    public static function generateUniqueNumber($length = 20): int
    {
        $uniqueNumber = str_shuffle(time() . random_int(111111111 , 999999999));

        $randomDigitNumber = '';
        for ($i = 0; $i < $length; $i++) {
            $randomDigit = substr($uniqueNumber, rand(0, strlen($uniqueNumber) - 1), 1);
            $randomDigitNumber .= $randomDigit;
        }


        return $randomDigitNumber;
    }

    protected static function booted()
    {
        static::creating(function ($payment) {
            $payment->unique_id = static::generateUniqueNumber(10);
        });
    }
}
