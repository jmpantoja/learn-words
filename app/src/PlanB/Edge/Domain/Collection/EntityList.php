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

namespace PlanB\Edge\Domain\Collection;


use PlanB\Edge\Domain\Collection\Exception\ValueIsNotAnEntityException;
use PlanB\Edge\Domain\Entity\EntityInterface;

abstract class EntityList extends TypedList
{
    /**
     * @inheritDoc
     */
    protected function ensureValueIsValid($value): void
    {
        parent::ensureValueIsValid($value);

        if (!($value instanceof EntityInterface)) {
            throw new ValueIsNotAnEntityException($value);
        }
    }

    /**
     * @inheritDoc
     */
    public function add($value): self
    {
        $this->ensureValueIsValid($value);
        parent::set((string)$value->getId(), $value);
        
        return $this;
    }
}
