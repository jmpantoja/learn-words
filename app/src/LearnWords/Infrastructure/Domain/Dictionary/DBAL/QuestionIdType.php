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

use LearnWords\Domain\Dictionary\QuestionId;
use PlanB\Edge\Infrastructure\Doctrine\DBAL\Type\EntityIdType;

final class QuestionIdType extends EntityIdType
{

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'QuestionId';
    }

    protected function fromString(string $value): QuestionId
    {
        // @phpstan-ignore-next-line
        return QuestionId::fromString($value);
    }
}
