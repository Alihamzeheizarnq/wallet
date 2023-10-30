<?php

namespace App\Enum\Approvals;

enum PaymentApproval: string
{
    case DELETED = 'deleted';
    case CREATED = 'created';
    case REJECTED = 'rejected';
    case APPROVED = 'approved';
}
