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


use PlanB\Edge\Domain\Collection\TypedList;

final class StatList extends TypedList
{

    public function getType(): string
    {
        return Stat::class;
    }

    public function sortByDate(): self
    {

        $stats = $this->getValues();
        usort($stats, function (Stat $first, Stat $second){
            $firstDate = $first->getDate();
            $secondDate = $second->getDate();

            return $firstDate->greaterThan($secondDate);
        });

        return static::collect($stats);
    }
}
