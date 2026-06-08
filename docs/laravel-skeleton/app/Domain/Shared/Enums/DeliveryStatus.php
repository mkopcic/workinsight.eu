<?php

namespace App\Domain\Shared\Enums;

enum DeliveryStatus: string
{
    case Pending = 'pending';
    case Delivered = 'delivered';
    case CanisterCollected = 'canister_collected';
    case NoAnswer = 'no_answer';
    case Rescheduled = 'rescheduled';
    case Failed = 'failed';
}
