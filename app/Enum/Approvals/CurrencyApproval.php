<?php

namespace App\Enum\Approvals;

enum CurrencyApproval: string
{
    case CREATED = 'created';
    case DEACTIVATED = 'deactivated';
    case ACTIVATED = 'activated';
}
