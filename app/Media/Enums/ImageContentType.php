<?php

namespace App\Media\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static JPG()
 * @method static static PNG()
 * @method static static WEBP()
 */
final class ImageContentType extends Enum
{
    public const JPG = 'jpg';

    public const PNG = 'png';

    public const WEBP = 'webp';

    public static function getDescription($value): string
    {
        return [
            self::JPG => 'A JPG image.',
            self::PNG => 'A PNG image.',
            self::WEBP => 'A WEBP image.',
        ][$value];
    }
}
