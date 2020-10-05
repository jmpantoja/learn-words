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


use PlanB\Edge\Domain\Enum\Enum;

/**
 * @method static self RIGHT()
 * @method static self WRONG()
 */
final class AnswerStatus extends Enum
{
    private const RIGHT = true;
    private const WRONG = false;

    public function isRight(): bool
    {
        return $this->equals(static::RIGHT());
    }

    public function isWrong(): bool
    {
        return $this->equals(static::WRONG());
    }
}
