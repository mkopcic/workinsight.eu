<?php

namespace App\Domain\Shared\Enums;

enum OrderType: string
{
    case Daily = 'daily';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
}
