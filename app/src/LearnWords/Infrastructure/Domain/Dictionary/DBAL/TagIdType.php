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

namespace LearnWords\Infrastructure\Domain\Dictionary\DBAL;


use LearnWords\Domain\Dictionary\EntryId;
use LearnWords\Domain\Dictionary\TagId;
use PlanB\Edge\Infrastructure\Doctrine\DBAL\Type\EntityIdType;

final class TagIdType extends EntityIdType
{

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'TagId';
    }

    protected function fromString(string $value): TagId
    {
        // @phpstan-ignore-next-line
        return TagId::fromString($value);
    }
}
