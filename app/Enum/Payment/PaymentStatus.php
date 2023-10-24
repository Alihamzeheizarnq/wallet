<?php

namespace App\Enum\Payment;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';


    public function translate(){
        switch ($this){
            case static::APPROVED:
                return __('payment.enums.' . self::APPROVED->value);
        }
    }
}
