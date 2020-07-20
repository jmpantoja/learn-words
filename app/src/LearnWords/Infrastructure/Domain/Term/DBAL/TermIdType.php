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

namespace LearnWords\Infrastructure\Domain\Term\DBAL;


use LearnWords\Domain\Term\TermId;
use PlanB\Edge\Infrastructure\Doctrine\DBAL\Type\EntityIdType;

final class TermIdType extends EntityIdType
{

    protected function fromString(string $value): TermId
    {
        // @phpstan-ignore-next-line
        return TermId::fromString($value);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'TermId';
    }
}
