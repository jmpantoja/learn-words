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
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class PersistenceCommand implements PersistenceCommandInterface
{
    private ?EntityInterface $entity;

    public static function make(array $input = [], ?EntityInterface $entity = null): self
    {
        return new static($input, $entity);
    }

    protected function __construct(array $input, EntityInterface $entity = null)
    {
        $this->entity = $entity;
        $propertyAccessor = new PropertyAccessor();
        foreach ($input as $name => $value) {
            $propertyAccessor->setValue($this, $name, $value);
        }
    }

    public function entity(): EntityInterface
    {
        return $this->entity ?? $this->newInstance();
    }

    abstract protected function newInstance(): EntityInterface;
}

