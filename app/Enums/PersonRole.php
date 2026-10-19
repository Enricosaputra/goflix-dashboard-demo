<?php

namespace App\Enums;

enum PersonRole: string
{
    case Actor = 'actor';
    case Actress = 'actress';
    case Director = 'director';

    public function label(): string
    {
        return __(ucfirst($this->value));
    }
}
