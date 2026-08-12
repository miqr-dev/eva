<?php

namespace App\Enums;

enum QuestionType: string
{
    case Scale = 'scale';
    case SingleChoice = 'single_choice';
    case FreeText = 'free_text';
    case YesNo = 'yes_no';
}
