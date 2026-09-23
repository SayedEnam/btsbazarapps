<?php

namespace App\Enums;

enum HeroSlideStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Active => 'bg-success',
            self::Inactive => 'bg-secondary',
        };
    }
}
