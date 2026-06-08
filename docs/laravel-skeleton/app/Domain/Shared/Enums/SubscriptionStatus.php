<?php

namespace App\Domain\Shared\Enums;

enum SubscriptionStatus: string
{
    case Active = 'active';
    case Paused = 'paused';
    case Ended = 'ended';
}
