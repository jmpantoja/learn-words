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

namespace LearnWords\Infrastructure\Domain\User\DBAL;


use LearnWords\Domain\User\StatId;
use PlanB\Edge\Infrastructure\Doctrine\DBAL\Type\EntityIdType;

final class StatIdType extends EntityIdType
{
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'StatId';
    }

    protected function fromString(string $value): StatId
    {
        // @phpstan-ignore-next-line
        return StatId::fromString($value);
    }
}
