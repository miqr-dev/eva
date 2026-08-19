<?php

namespace App\Enums;

enum ModuleTargetType: string
{
    case None = 'none';
    case Course = 'course';
    case Organization = 'organization';
    case Teacher = 'teacher';
}
