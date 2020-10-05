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

class Relevance
{
    use ValidableTrait;

    private int $relevance;

    public function __construct(int $relevance)
    {
        $this->ensure($relevance);
        $this->relevance = $relevance;
    }

    public static function getConstraints()
    {
        return new Constraints\Relevance();
    }

    public function toInt(): int
    {
        return $this->relevance;
    }
}
