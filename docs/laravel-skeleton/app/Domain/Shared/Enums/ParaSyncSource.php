<?php

namespace App\Domain\Shared\Enums;

enum ParaSyncSource: string
{
    case Api = 'api';
    case File = 'file';
}
