<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PENDING()
 * @method static static PROCESSING()
 * @method static static REJECTED()
 * @method static static COMPLETED()
 */
final class OrderStatus extends Enum
{
    public const PENDING = 1;

    public const PROCESSING = 2;

    public const REJECTED = 3;

    public const COMPLETED = 4;
}
