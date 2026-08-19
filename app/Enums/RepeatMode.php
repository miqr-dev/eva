<?php

namespace App\Enums;

enum RepeatMode: string
{
    case Once = 'once';
    case PerTarget = 'per_target';
}
