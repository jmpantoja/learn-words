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

namespace LearnWords\Infrastructure\Domain\Word\DBAL;


use LearnWords\Domain\Word\WordId;
use PlanB\Edge\Infrastructure\Doctrine\DBAL\Type\EntityIdType;

final class WordIdType extends EntityIdType
{

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'WordId';
    }

    protected function fromString(string $value): WordId
    {
        // @phpstan-ignore-next-line
        return WordId::fromString($value);
    }
}
