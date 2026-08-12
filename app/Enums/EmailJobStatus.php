<?php

namespace App\Enums;

enum EmailJobStatus: string
{
    case Draft = 'draft';
    case Scheduled = 'scheduled';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';
    case Cancelled = 'cancelled';
}
