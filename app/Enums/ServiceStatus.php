<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ACTIVE()
 * @method static static INACTIVE()
 */
final class ServiceStatus extends Enum
{
    public const ACTIVE = 'active';

    public const INACTIVE = 'inactive';
}
