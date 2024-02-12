<?php declare(strict_types=1);

namespace App\Enums;

enum SocialProvider: string
{
    case Line = 'line';
    case Google = 'google';
    case Facebook = 'facebook';
}