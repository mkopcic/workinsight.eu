<?php

namespace App\Domain\Shared\Enums;

enum OrderLineStatus: string
{
    case Pending = 'pending';
    case Locked = 'locked';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
}
