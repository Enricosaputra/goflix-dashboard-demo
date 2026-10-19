<?php

namespace App\Enums;

// How a user signs in to the app. Production stores the same set as numbers (1 Facebook ... 5 Apple).
enum LoginType: string
{
    case Google = 'google';
    case Facebook = 'facebook';
    case Apple = 'apple';
    case Otp = 'otp';
    case Email = 'email';

    public function label(): string
    {
        return match ($this) {
            self::Google => 'Google',
            self::Facebook => 'Facebook',
            self::Apple => 'Apple',
            self::Otp => __('Phone (OTP)'),
            self::Email => __('Email'),
        };
    }
}
