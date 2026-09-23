<?php

namespace App\Enums;

enum HeroSlideDisplayMode: string
{
    case TextAndButton = 'text_and_button';
    case ImageOnly = 'image_only';

    public function label(): string
    {
        return match ($this) {
            self::TextAndButton => 'Text & Button',
            self::ImageOnly => 'Image Only',
        };
    }
}
