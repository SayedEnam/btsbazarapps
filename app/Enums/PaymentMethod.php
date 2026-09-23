<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case Bank = 'bank';
    case MobileBanking = 'mobile_banking';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Cash',
            self::Bank => 'Bank',
            self::MobileBanking => 'Mobile Banking',
            self::Other => 'Other',
        };
    }
}
