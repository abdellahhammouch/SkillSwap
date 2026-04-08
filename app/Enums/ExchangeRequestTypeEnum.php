<?php

namespace App\Enums;

enum ExchangeRequestTypeEnum: string
{
    case HELP_REQUEST = 'help_request';
    case HELP_OFFER = 'help_offer';
}
