<?php

namespace App\Enum\Payment;

enum Status: string
{
    case PENDING = 'Pending';
    case APPROVED = 'approved';
    case REJECTED = 'Rejected';


    public function translate(){
        switch ($this){
            case static::APPROVED:
                return __('payment.enums.' . self::APPROVED->value);
        }
    }
}
