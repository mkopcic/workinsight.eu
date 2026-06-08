<?php

namespace App\Domain\Shared\Enums;

enum PensionerStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
}
