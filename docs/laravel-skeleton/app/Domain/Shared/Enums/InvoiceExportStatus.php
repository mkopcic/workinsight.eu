<?php

namespace App\Domain\Shared\Enums;

enum InvoiceExportStatus: string
{
    case Pending = 'pending';
    case Sent = 'sent';
    case Failed = 'failed';
    case Acknowledged = 'acknowledged';
}
