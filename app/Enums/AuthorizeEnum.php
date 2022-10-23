<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static DECLINED()
 * @method static static APPROVED ()
 */
final class AuthorizeEnum extends Enum
{
    const DECLINED = 0;

    const APPROVED = 1;
}
