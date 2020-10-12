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
 * @method static self TODAY()
 * @method static self DAILY()
 * @method static self FAILED()
 */
final class ExamType extends Enum
{
    private const TODAY = 'New words';
    private const DAILY = 'Daily review';
    private const FAILED = 'The most wrong';

    public static function byKey(string $value): Enum
    {
        $value = strtoupper($value);
        return parent::byKey($value);
    }


}
