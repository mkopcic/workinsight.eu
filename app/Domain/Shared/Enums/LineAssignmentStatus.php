<?php

namespace App\Domain\Shared\Enums;

enum LineAssignmentStatus: string
{
    case Planned = 'planned';
    case InProgress = 'in_progress';
    case Completed = 'completed';
}
