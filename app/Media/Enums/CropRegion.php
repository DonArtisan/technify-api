<?php

namespace App\Media\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static BOTTOM()
 * @method static static CENTER()
 * @method static static LEFT()
 * @method static static RIGHT()
 * @method static static TOP()
 */
final class CropRegion extends Enum
{
    public const BOTTOM = 'bottom';

    public const CENTER = 'center';

    public const LEFT = 'left';

    public const RIGHT = 'right';

    public const TOP = 'top';

    public static function getDescription($value): string
    {
        return [
            self::BOTTOM => 'Keep the bottom of the image.',
            self::CENTER => 'Keep the center of the image.',
            self::LEFT => 'Keep the left of the image.',
            self::RIGHT => 'Keep the right of the image.',
            self::TOP => 'Keep the top of the image.',
        ][$value];
    }
}
