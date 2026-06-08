<?php

namespace App\Domain\Shared\Enums;

enum ProfileStatus: string
{
    case Active = 'active';
    case Paused = 'paused';
    case Inactive = 'inactive';
}
