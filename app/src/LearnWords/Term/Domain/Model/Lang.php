<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace LearnWords\Term\Domain\Model;


use PlanB\Edge\Domain\Enum\Enum;

/**
 * @method static Lang ENGLISH()
 * @method static Lang SPANISH()
 */
final class Lang extends Enum
{
    private const ENGLISH = 'en';
    private const SPANISH = 'es';
}
