<?php

namespace App\Domain\Shared\Enums;

enum ContractStatus: string
{
    case Draft = 'draft';
    case Generated = 'generated';
    case Sent = 'sent';
    case Signed = 'signed';
    case Expired = 'expired';
    case Terminated = 'terminated';
}
