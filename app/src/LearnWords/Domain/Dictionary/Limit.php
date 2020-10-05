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


use PlanB\Edge\Domain\Validator\ValidableTrait;

class Limit
{
    use ValidableTrait;

    private int $limit;

    public function __construct(int $limit)
    {
        $this->ensure($limit);
        $this->limit = $limit;
    }

    public static function getConstraints()
    {
        return new Constraints\Limit();
    }

    public function toInt(): int
    {
        return $this->limit;
    }
}
