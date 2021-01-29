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


use PlanB\Edge\Domain\Collection\TypedList;

final class VocabularyList extends TypedList
{

    public function getType(): string
    {
        return Vocabulary::class;
    }

}