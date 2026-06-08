<?php

namespace App\Domain\Shared\Enums;

enum MenuStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';
}
