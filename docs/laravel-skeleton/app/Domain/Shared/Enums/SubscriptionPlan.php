<?php

namespace App\Domain\Shared\Enums;

enum SubscriptionPlan: string
{
    case Daily = 'daily';
    case Monthly = 'monthly';
}
