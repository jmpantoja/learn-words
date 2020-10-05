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

namespace LearnWords\Domain\User;


use Carbon\CarbonImmutable;
use PlanB\Edge\Domain\Enum\Enum;

/**
 * @method static self INITIAL()
 * @method static self EACH_DAY()
 * @method static self EACH_THREE_DAYS()
 * @method static self EACH_WEEK()
 * @method static self EACH_TWO_WEEKS()
 * @method static self EACH_MONTH()
 */
final class Leitner extends Enum
{
    private const INITIAL = 0;
    private const EACH_DAY = 1;
    private const EACH_THREE_DAYS = 2;
    private const EACH_WEEK = 3;
    private const EACH_TWO_WEEKS = 4;
    private const EACH_MONTH = 5;

    public function next(): Leitner
    {
        $nextValue = $this->getValue() + 1;

        if (static::isValid($nextValue)) {
            return static::byValue($nextValue);
        }

        return $this;
    }

    public function getDateWithIncrement(): CarbonImmutable
    {
        $today = CarbonImmutable::today();
        $increment = $this->calculeIncrement();

        return $today->addDays($increment);
    }

    private function calculeIncrement(): int
    {
        switch ($this->getValue()) {
            case static::INITIAL:
                return 0;
            case static::EACH_DAY:
                return 1;
            case static::EACH_THREE_DAYS:
                return 3;
            case static::EACH_WEEK:
                return 7;
            case static::EACH_TWO_WEEKS:
                return 14;
            case static::EACH_MONTH:
                return 30;
        }

    }
}
