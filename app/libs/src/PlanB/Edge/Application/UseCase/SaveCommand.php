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
use Throwable;

abstract class SaveCommand implements WriteCommandInterface
{
    private ?EntityInterface $entity;

    public static function make(array $input = [], ?EntityInterface $entity = null): self
    {
        return new static($input, $entity);
    }

    protected function __construct(array $input, EntityInterface $entity = null)
    {
        try {
            $this->entity = $this->build($input, $entity);
        } catch (Throwable $exception) {
            $this->entity = $entity;
        }
    }

    protected function build($input, $entity): EntityInterface
    {
        $data = $this->resolve($input);

        if (is_null($entity)) {
            return $this->create($data);
        }
        return $this->update($data, $entity);
    }

    /**
     * Se puede heredar este método para aplicar un OptionResolver, si es necesario
     *
     * @param array $input
     * @return array
     */
    protected function resolve(array $input): array
    {
        return $input;
    }

    public function entity(): EntityInterface
    {
        return $this->entity;
    }

    abstract protected function create(array $data): EntityInterface;

    abstract protected function update(array $data): EntityInterface;

}

