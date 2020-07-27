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

namespace LearnWords\Domain\Word;


use PlanB\Edge\Domain\Collection\TypedList;

final class TagList extends TypedList
{

    public function getType(): string
    {
        return Tag::class;
    }
}
