<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PENDING()
 * @method static static COMPLETED()
 */
final class DeliveryStatus extends Enum
{
    public const PENDING = 0;

    public const COMPLETED = 1;
}
