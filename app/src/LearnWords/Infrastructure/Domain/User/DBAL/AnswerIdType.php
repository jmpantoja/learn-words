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


use LearnWords\Domain\Dictionary\EntryId;
use LearnWords\Domain\User\AnswerId;
use PlanB\Edge\Infrastructure\Doctrine\DBAL\Type\EntityIdType;

final class AnswerIdType extends EntityIdType
{
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'AnswerId';
    }

    protected function fromString(string $value): AnswerId
    {
        // @phpstan-ignore-next-line
        return AnswerId::fromString($value);
    }
}
