<?php

namespace App\Enums;

enum TripStatusEnum: string
{
    case ACCEPTED = 'ACCEPTED';
    case STARTED = 'STARTED';
    case ENDED = 'ENDED';
}