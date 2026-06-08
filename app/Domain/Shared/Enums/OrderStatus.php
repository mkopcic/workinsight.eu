<?php

namespace App\Domain\Shared\Enums;

enum OrderStatus: string
{
    case Draft = 'draft';
    case Confirmed = 'confirmed';
    case PartiallyDelivered = 'partially_delivered';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
