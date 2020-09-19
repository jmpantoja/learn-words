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
use PlanB\Edge\Infrastructure\Doctrine\DBAL\Type\EntityIdType;

final class EntryIdType extends EntityIdType
{

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'EntryId';
    }

    protected function fromString(string $value): EntryId
    {
        // @phpstan-ignore-next-line
        return EntryId::fromString($value);
    }
}
