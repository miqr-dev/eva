<?php

namespace App\Enums;

enum OrganizationUnitType: string
{
    case Institution = 'institution';
    case Faculty = 'faculty';
    case Department = 'department';
    case Program = 'program';
    case Other = 'other';
}
