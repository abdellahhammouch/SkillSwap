<?php

namespace App\Enums;

enum RequestStatusEnum: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REFUSED = 'refused';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
    case COMPLETED = 'completed';
}
