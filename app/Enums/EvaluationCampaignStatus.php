<?php

namespace App\Enums;

enum EvaluationCampaignStatus: string
{
    case Draft = 'draft';
    case Scheduled = 'scheduled';
    case Active = 'active';
    case Closed = 'closed';
    case Archived = 'archived';
}
