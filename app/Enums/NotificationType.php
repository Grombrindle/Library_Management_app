<?php

namespace App\Enums;

enum NotificationType: string
{
    case SYSTEM_UPDATE = 'systemUpdate';
    case NEW_MESSAGE = 'newMessage';
    case ORDER_UPDATE = 'orderUpdate';
    case PROMOTION = 'promotion';

}
