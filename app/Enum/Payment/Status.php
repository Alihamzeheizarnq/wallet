<?php

namespace App\Enum\Payment;

enum Status: string
{
    case PENDING = 'Pending';
    case APPROVED = 'Approved';
    case REJECTED = 'Rejected';
}
