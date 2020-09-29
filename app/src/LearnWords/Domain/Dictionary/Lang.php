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

namespace LearnWords\Domain\Dictionary;


use PlanB\Edge\Domain\Enum\Enum;

/**
 * @method static self SPANISH()
 * @method static self ENGLISH()
 */
final class Lang extends Enum
{
    private const SPANISH = 'SPANISH';
    private const ENGLISH = 'ENGLISH';

}