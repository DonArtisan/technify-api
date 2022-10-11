<?php declare(strict_types=1);

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
    const PENDING = 1;
    const PROCESSING = 2;
    const REJECTED = 3;
    const COMPLETED = 4;
}
