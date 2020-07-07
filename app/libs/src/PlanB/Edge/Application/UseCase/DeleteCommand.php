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

namespace PlanB\Edge\Application\UseCase;


use PlanB\Edge\Domain\Entity\EntityInterface;

abstract class DeleteCommand implements PersistenceCommandInterface
{
    private ?EntityInterface $entity;

    protected function __construct(EntityInterface $entity)
    {
        $this->entity = $entity;
    }

    public static function make(EntityInterface $entity = null): self
    {
        return new static($entity);
    }

    public function entity(): EntityInterface
    {
        return $this->entity;
    }
}

